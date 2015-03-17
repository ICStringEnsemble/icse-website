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

    protected function buildForm($form)
    {
        $form->add('name', 'text');
        $form->add('address', 'textarea', ['required' => false]);
        $form->add('embed_map', 'text', ['required' => false]);
    }
}
