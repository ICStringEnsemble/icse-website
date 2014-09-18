<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

use Icse\MembersBundle\Form\Type\DateTimeType;

use Icse\PublicBundle\Entity\Venue;
use Common\Tools; 

class VenueController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcsePublicBundle:Venue');
    }

    protected function getViewName()
    {
        return 'IcseMembersBundle:Admin:venues.html.twig';
    }

    protected function newInstance()
    {
        return new Venue();
    }

    protected function getListContent()
    {
        $entities = $this->repository()->findBy(array(), array('name'=>'asc'));

        $fields = [
            'Name' => function(Venue $x){return $x->getName();},
            'Address' => function(Venue $x){return $x->getAddress();},
            'Last updated' => function(Venue $x){return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();},
        ];
        return ["fields" => $fields, "entities" => $entities];
    }

    protected function getForm($entity)
    {
        $form = $this->createFormBuilder($entity)
        ->add('name', 'text')
        ->add('address', 'textarea', ['required' => false])
        ->add('embed_map', 'text', ['required' => false])
        ->getForm(); 
        return $form;
    }

    protected function putData($request, $entity)
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
