<?php

namespace Icse\MembersBundle\Controller\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;


abstract class EntityAdminController extends Controller
{
    private $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    abstract protected function repository();

    abstract protected function newInstance();

    abstract protected function getListContent();

    protected function getViewName()
    {
        return 'IcseMembersBundle:Admin:entity_admin_template.html.twig';
    }

    protected function getListViewName()
    {
        return 'IcseMembersBundle:Admin/entity_instance_list:table.html.twig';
    }

    protected function initialiseCreatedInstance($entity) {}
    protected function buildForm(FormBuilder $form) {}
    protected function buildCreationForm(FormBuilder $form) {$this->buildForm($form);}
    protected function buildEditForm(FormBuilder $form) {$this->buildForm($form);}
    protected function preCheckFormValid(Form $form, $entity) {}
    protected function prePersistEntity($entity) {}
    protected function postCreateEntity($entity) {}
    protected function postEditEntity($entity) {}
    protected function indexData() {return [];}
    protected function getFormCollectionNames() {return [];}

    public function indexAction()
    {
        $entity = $this->newInstancePrototype();
        $form = $this->getEditForm($entity);

        return $this->render($this->getViewName(), array_merge([
            'form' => $form->createView()
        ], $this->indexData()));
    }

    /**
     * @param Request $request
     * @param $arg
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function routerAction(Request $request, $arg)
    {
        if ($request->getMethod() == "GET")
        {
            if ($arg == null)
            {
                return $this->indexAction($request);
            }
            else if ($arg == "list")
            {
                return $this->listAction($request);
            }
        }
        else if ($request->getMethod() == "POST")
        {
            if ($arg == null)
            {
                return $this->createAction($request);
            }
            else
            {
                $op = $request->query->get('op');
                return $this->instanceOperationAction($request, $arg, $op);
            }
        }
        else if ($request->getMethod() == "PUT" && $arg != null)
        {
            return $this->updateAction($request, $arg);
        }
        else if ($request->getMethod() == "DELETE" && $arg != null)
        {
            return $this->deleteAction($arg);
        }
        throw $this->createNotFoundException();
    }

    public function listAction()
    {
        $list_content = $this->getListContent();
        return $this->render($this->getListViewName(), ['list_content' => $list_content]);
    }

    public function createAction(Request $request)
    {
        $entity = $this->newInstance();
        $this->initialiseCreatedInstance($entity);
        $form = $this->getCreationForm($entity);
        $response = $this->putData($form, $request, $entity);
        if ($response->formSuccessful())
        {
            $this->postCreateEntity($entity);
        }
        return $response;
    }

    public function updateAction(Request $request, $id)
    {
        $entity = $this->getEntityById($id, true);
        $form = $this->getEditForm($entity);
        $response = $this->putData($form, $request, $entity);
        if ($response->formSuccessful())
        {
            $this->postEditEntity($entity);
        }
        return $response;
    }

    public function deleteAction($id)
    {
        $entity = $this->getEntityById($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        return $this->get('ajax_response_gen')->returnSuccess();
    }

    private function getFormCollections($entity)
    {
        foreach($this->getFormCollectionNames() as $name)
        {
            yield $name => $this->accessor->getValue($entity, $name);
        }
    }

    protected function getEntityById($id, $reindex_collections=false)
    {
        $collection_names = $reindex_collections ? $this->getFormCollectionNames() : [];

        /** @var QueryBuilder $qb */
        $qb = $this->repository()->createQueryBuilder('entity');
        $qb->setParameter('id', $id)->where($qb->expr()->eq('entity.id', ':id'));
        foreach($collection_names as $i => $c)
        {
            $qb->addSelect("c$i");
            $qb->leftJoin("entity.$c", "c$i", null, null, "c$i.id");
        }
        $entity = $qb->getQuery()->getOneOrNullResult();

        if (!$entity) throw $this->createNotFoundException('Entity does not exist');
        return $entity;
    }

    protected function instanceOperationAction($request, $id, $op)
    {
        throw $this->createNotFoundException();
    }

    protected function newInstancePrototype()
    {
        return $this->newInstance();
    }

    protected function timeagoDate(\DateTime $datetime)
    {
        return '<abbr class="timeago" title="' . $datetime->format('c') . '">' . $datetime->format('Y-m-d H:i:s'). '</abbr>';
    }

    protected function getCreationForm($entity)
    {
        $form = $this->createFormBuilder($entity, ['cascade_validation' => true]);
        $form->setMethod('POST');
        $this->buildCreationForm($form);
        return $form->getForm();
    }

    protected function getEditForm($entity)
    {
        $form = $this->createFormBuilder($entity, ['cascade_validation' => true]);
        $form->setMethod('PUT');
        $this->buildEditForm($form);
        return $form->getForm();
    }

    protected function putData(Form $form, Request $request, $entity)
    {
        $collections_before = [];
        foreach($this->getFormCollections($entity) as $name => $collection)
        {
            $collections_before[$name] = new ArrayCollection($collection->toArray());
        }

        $form->handleRequest($request);

        if (method_exists($entity,'setUpdatedAt')) $entity->setUpdatedAt(new \DateTime());
        if (method_exists($entity,'setUpdatedBy')) $entity->setUpdatedBy($this->getUser());

        $this->preCheckFormValid($form, $entity);

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            $this->prePersistEntity($entity);

            $em->persist($entity);

            foreach($this->getFormCollections($entity) as $name => $collection)
            {
                $old_collection = $collections_before[$name];
                foreach($old_collection as $i) if (!$collection->contains($i)) $em->remove($i);
                foreach($collection as $i) $em->persist($i);
            }

            $em->flush();
            return $this->get('ajax_response_gen')->returnSuccess(['entity' => $entity]);
        }
        else
        {
            // Cancel any changes
            if ($em->contains($entity))
            {
                $em->refresh($entity);

                foreach($this->getFormCollections($entity) as $collection)
                {
                    foreach($collection as $i) if ($em->contains($i)) $em->refresh($i);
                }
            }
            return $this->get('ajax_response_gen')->returnFail($form);
        }
    }
}
