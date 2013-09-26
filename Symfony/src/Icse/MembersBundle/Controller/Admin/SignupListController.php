<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

use Icse\PublicBundle\Entity\Subscriber;
use Common\Tools; 

class SignupListController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcsePublicBundle:Subscriber');
    }

    protected function viewName()
    {
        return 'IcseMembersBundle:Admin:signup_list.html.twig';
    }

    protected function newInstance()
    {
        return new Subscriber();
    }

    protected function getTableContent()
    {
        $dm = $this->getDoctrine(); 
        $subscribers = $dm->getRepository('IcsePublicBundle:Subscriber')->findAllThisYear();

        $columns = array(
            // array('heading' => 'ID', 'cell' => function($x){return $x->getID();}),
            array('heading' => 'Email', 'cell' => function($x){return $x->getEmail();}),
            array('heading' => 'Name', 'cell' => function($x){return $x->getFullName();}),
            array('heading' => 'Login', 'cell' => function($x){return $x->getLogin();}),
            array('heading' => 'Department', 'cell' => function($x){return $x->getDepartment();}),
            array('heading' => 'Player?', 'cell' => function($x){return $x->getPlayer() ? "Yes" : "No";}),
            array('heading' => 'Instrument', 'cell' => function($x){return $x->getInstrument();}),
            array('heading' => 'Standard', 'cell' => function($x){return $x->getStandard();}),
            array('heading' => 'Submitted at', 'cell' => function($x){return $x->getSubscribedAt()->format('j/m/y H:i:s');}),
            // array('heading' => 'Last updated', 'cell' => function($x){return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();}),
            );
        return array("columns" => $columns, "entities" => $subscribers);
    }

    protected function getForm($subscriber)
    {
        $form = $this->createFormBuilder($subscriber)
        ->getForm(); 
        return $form;
    }

    protected function putData($request, $subscriber)
    {
        // $is_new = ($subscriber->getID() === null);
        $form = $this->getForm($subscriber);
        $form->bind($request);

        // $subscriber->setUpdatedAt(new \DateTime());
        // $subscriber->setUpdatedBy($this->get('security.context')->getToken()->getUser());

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            $em->persist($subscriber);
            $em->flush();
            return $this->get('ajax_response_gen')->returnSuccess();
        }
        else
        {
            // Cancel any changes
            if ($em->contains($subscriber))
            {
                $em->refresh($subscriber);
            }
            return $this->get('ajax_response_gen')->returnFail($form);
        }  
    }



}
