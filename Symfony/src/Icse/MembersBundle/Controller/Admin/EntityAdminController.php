<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


abstract class EntityAdminController extends Controller
{
    abstract protected function repository();

    abstract protected function newInstance();

    abstract protected function getTableContent();

    abstract protected function getForm($entity);

    abstract protected function putData($request, $entity);

    protected function viewName()
    {
        return 'IcseMembersBundle:Admin:entity_admin_template.html.twig';
    }

    public function indexAction()
    {
        $entity = $this->newInstance();
        $form = $this->getForm($entity);
        $table_content = $this->getTableContent();

        return $this->render($this->viewName(), array(
            'table_content' => $table_content,
            'form' => $form->createView()
        ));
    }

    public function routerAction(Request $request, $arg)
    {
        if ($request->getMethod() == "GET")
        {
            if ($arg == null)
            {
                return $this->indexAction($request);
            }
            else if ($arg == "table")
            {
                return $this->tableAction($request);
            }
        }
        else if ($request->getMethod() == "POST" && $arg == null)
        {
            return $this->createAction($request);
        }
        else if ($request->getMethod() == "PUT" && $arg != null)
        {
            return $this->updateAction($request, $arg);
        }
        else if ($request->getMethod() == "DELETE" && $arg != null)
        {
            return $this->deleteAction($arg);
        }
        throw $this->createNotFoundException('Page does not exist');
    }

    public function tableAction()
    {
        $table_content = $this->getTableContent();
        return $this->render('IcseMembersBundle:Admin:table_fragment.html.twig', array('table_content' => $table_content)); 
    }

    public function createAction(Request $request)
    {
        $entity = $this->newInstance();
        return $this->putData($request, $entity);
    }

    public function updateAction(Request $request, $id)
    {
        $entity = $this->repository()->findOneById($id);
        if (!$entity) {
            throw $this->createNotFoundException('Entity does not exist'); 
        }
        return $this->putData($request, $entity);
    }

    public function deleteAction($id) {
        $dm = $this->getDoctrine(); 
        $entity = $this->repository()->findOneById($id);
        if ($entity) {
            $em = $dm->getManager();
            $em->remove($entity);
            $em->flush();
        }
        return $this->get('ajax_response_gen')->returnSuccess();
    }

    protected function timeagoDate($datetime)
    {
        return '<abbr class="timeago" title="' . $datetime->format('c') . '">' . $datetime->format('Y-m-d H:i:s'). '</abbr>';
    }
}
