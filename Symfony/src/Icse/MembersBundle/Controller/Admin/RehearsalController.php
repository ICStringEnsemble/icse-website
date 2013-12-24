<?php

namespace Icse\MembersBundle\Controller\Admin;

use Icse\MembersBundle\Entity\Rehearsal;

class RehearsalController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcseMembersBundle:Rehearsal');
    }

    protected function viewName()
    {
        return 'IcseMembersBundle:Admin:rehearsals.html.twig';
    }

    protected function newInstance()
    {
        return new Rehearsal();
    }

    protected function getTableContent()
    {
        $dm = $this->getDoctrine(); 
        $rehearsals = $dm->getRepository('IcseMembersBundle:Rehearsal')->findBy(array(), array('starts_at'=>'desc'));

        $columns = array(
            // array('heading' => 'ID', 'cell' => function($x){return $x->getID();}),
            array('heading' => 'Date', 'cell' => function($x){return $x->getStartsAt()? $x->getStartsAt()->format('D jS F Y') : "?";}),
            array('heading' => 'Time', 'cell' => function($x){return $x->getStartsAt()? $x->getStartsAt()->format('g:ia') : "?";}),
            array('heading' => 'Where', 'cell' => function($x){return $x->getLocation() ? $x->getLocation()->getName() : "?";}),
            array('heading' => 'Title', 'cell' => function($x){return $x->getName();}),
            array('heading' => 'Comments', 'cell' => function($x){return $x->getComments();}),
            array('heading' => 'Last updated', 'cell' => function($x){return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();}),
            );
        return array("columns" => $columns, "entities" => $rehearsals);
    }

    protected function getForm($rehearsal)
    {
        $rehearsal->setStartsAt(new \DateTime("this friday 6pm"));
        $form = $this->createFormBuilder($rehearsal)
        ->add('starts_at', 'datetime12', array(
                'date_widget' => 'single_text'
            ,   'time_widget' => 'single_text'
            ,   'date_format' => 'dd/MM/yy'
            ))
        ->add('location', 'entity', array(
                'class' => 'IcsePublicBundle:Venue',
                'property' => 'name',
                'required' => false,
            ))
        ->add('name', 'text', array('required' => false, 'label' => 'Title'))
        ->add('comments', 'textarea', array('required' => false))
        ->getForm(); 
        return $form;
    }

    protected function putData($request, $rehearsal)
    {
        // $is_new = ($rehearsal->getID() === null);
        $form = $this->getForm($rehearsal);
        $form->bind($request);

        $rehearsal->setUpdatedAt(new \DateTime());
        $rehearsal->setUpdatedBy($this->get('security.context')->getToken()->getUser());

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            $em->persist($rehearsal);
            $em->flush();
            return $this->get('ajax_response_gen')->returnSuccess();
        }
        else
        {
            // Cancel any changes
            if ($em->contains($rehearsal))
            {
                $em->refresh($rehearsal);
            }
            return $this->get('ajax_response_gen')->returnFail($form);
        }  
    }



}
