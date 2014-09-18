<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


abstract class EntityAdminController extends Controller
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    abstract protected function repository();

    abstract protected function newInstance();

    abstract protected function getListContent();

    /**
     * @param $entity
     * @return \Symfony\Component\Form\Form
     */
    abstract protected function getForm($entity);

    abstract protected function putData($request, $entity);

    protected function getViewName()
    {
        return 'IcseMembersBundle:Admin:entity_admin_template.html.twig';
    }

    protected function getListViewName()
    {
        return 'IcseMembersBundle:Admin/entity_instance_list:table.html.twig';
    }

    protected function indexData()
    {
        return [];
    }

    public function indexAction()
    {
        $entity = $this->newInstancePrototype();
        $form = $this->getForm($entity);

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
        return $this->putData($request, $entity);
    }

    protected function getEntityById($id, $finder = 'findOneById') {
        $entity = $this->repository()->$finder($id);
        if (!$entity) {
            throw $this->createNotFoundException('Entity does not exist');
        }
        return $entity;
    }

    public function updateAction(Request $request, $id)
    {
        $entity = $this->getEntityById($id, $this->updateEntityFinder());
        return $this->putData($request, $entity);
    }

    protected function updateEntityFinder()
    {
        return 'findOneById';
    }

    public function deleteAction($id)
    {
        $entity = $this->getEntityById($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        return $this->get('ajax_response_gen')->returnSuccess();
    }

    /**
     * @param $request
     * @param $id
     * @param $op
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Response
     */
    protected function instanceOperationAction(/** @noinspection PhpUnusedParameterInspection */
        $request, $id, $op)
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
}
