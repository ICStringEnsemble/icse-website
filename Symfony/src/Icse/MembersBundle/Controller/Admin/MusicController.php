<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

use Icse\MembersBundle\Form\Type\DateTimeType;

use Icse\PublicBundle\Entity\PieceOfMusic;
use Common\Tools; 

class MusicController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcsePublicBundle:PieceOfMusic');
    }

    protected function viewName()
    {
        return 'IcseMembersBundle:Admin:music.html.twig';
    }

    protected function newInstance()
    {
        return new PieceOfMusic();
    }

    protected function getTableContent()
    {
        $entities = $this->repository()->findBy(array(), array('name'=>'asc'));

        $columns = array(
            array('heading' => 'Composer', 'cell' => function($x){return $x->getComposer();}),
            array('heading' => 'Name', 'cell' => function($x){return $x->getName();}),
            array('heading' => 'Last updated', 'cell' => function($x){return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();}),
            );
        return array("columns" => $columns, "entities" => $entities);
    }

    protected function getForm($entity)
    {
        $form = $this->createFormBuilder($entity)
        ->add('name', 'text')
//        ->add('address', 'textarea', ['required' => false])
//        ->add('embed_map', 'text', ['required' => false])
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
