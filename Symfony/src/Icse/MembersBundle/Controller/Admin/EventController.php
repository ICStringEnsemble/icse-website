<?php

namespace Icse\MembersBundle\Controller\Admin;

use Icse\PublicBundle\Entity\Event;

class EventController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcsePublicBundle:Event');
    }

    protected function viewName()
    {
        return 'IcseMembersBundle:Admin:events.html.twig';
    }

    protected function newInstance()
    {
        return new Event();
    }

    protected function getTableContent()
    {
        $entities = $this->repository()->findBy(array(), array('starts_at'=>'desc'));

        $columns = array(
            array('heading' => 'Name', 'cell' => function($x){/* @var $x Event */ return $x->getName();}),
            array('heading' => 'Date', 'cell' => function($x){/* @var $x Event */ return $x->getStartsAt()? $x->getStartsAt()->format('D jS F Y') : "?";}),
            array('heading' => 'Time', 'cell' => function($x){/* @var $x Event */ return $x->getStartsAt()? $x->getStartsAt()->format('g:ia') : "?";}),
            array('heading' => 'Where', 'cell' => function($x){/* @var $x Event */ return $x->getLocation() ? $x->getLocation()->getName() : "?";}),
            array('heading' => 'Last updated', 'cell' => function($x){/* @var $x Event */ return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();}),
            );
        return array("columns" => $columns, "entities" => $entities);
    }

    protected function getForm($entity)
    {
        $form = $this->createFormBuilder($entity)
        ->add('name', 'text')
        ->getForm(); 
        return $form;
    }

    protected function putData($request, $entity)
        /* @var $entity Event */
    {
        $form = $this->getForm($entity);
        $form->bind($request);

        $entity->setUpdatedAt(new \DateTime());
        $entity->setUpdatedBy($this->get('security.context')->getToken()->getUser());

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            $em->persist($entity);
            $em->flush();
            return $this->get('ajax_response_gen')->returnSuccess();
        }
        else
        {
            // Cancel any changes
            if ($em->contains($entity))
            {
                $em->refresh($entity);
            }
            return $this->get('ajax_response_gen')->returnFail($form);
        }  
    }



}
