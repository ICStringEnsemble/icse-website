<?php

namespace Icse\MembersBundle\Controller\Admin;

use Icse\PublicBundle\Entity\Event;

class EventController extends EntityAdminController
{
    /**
     * @return \Icse\PublicBundle\Entity\EventRepository
     */
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
        $entities = $this->repository()->findAllEventsDescUnknownFirst();

        $columns = array(
            array('heading' => 'Name', 'cell' => function(Event $x){return $x->getName();}),
            array('heading' => 'Date', 'cell' => function(Event $x){return $x->getStartsAt()? $x->getStartsAt()->format('D jS F Y') : "?";}),
            array('heading' => 'Time', 'cell' => function(Event $x){return $x->getStartsAt()? $x->getStartsAt()->format('g:ia') : "?";}),
            array('heading' => 'Where', 'cell' => function(Event $x){return $x->getLocation() ? $x->getLocation()->getName() : "?";}),
            array('heading' => 'Last updated', 'cell' => function(Event $x){return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();}),
            );
        return array("columns" => $columns, "entities" => $entities);
    }

    protected function getForm($entity)
    {
        $form = $this->createFormBuilder($entity)
        ->add('name', 'text')
        ->add('starts_at', 'datetime12', array(
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'dd/MM/yy',
        ))
        ->add('location', 'entity', array(
            'class' => 'IcsePublicBundle:Venue',
            'property' => 'name',
            'required' => false,
        ))
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
