<?php

namespace Icse\MembersBundle\Controller\Admin;

use Icse\MembersBundle\Entity\Rehearsal;
use Icse\MembersBundle\Form\Type\EndTimeType;

class RehearsalController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcseMembersBundle:Rehearsal');
    }

    protected function getViewName()
    {
        return 'IcseMembersBundle:Admin:rehearsals.html.twig';
    }

    protected function newInstance()
    {
        return new Rehearsal();
    }

    protected function getListContent()
    {
        $dm = $this->getDoctrine(); 
        $rehearsals = $dm->getRepository('IcseMembersBundle:Rehearsal')->findBy(array(), array('starts_at'=>'desc'));

        $fields = [
            'Date' => function(Rehearsal $x){return $x->getStartsAt()? $x->getStartsAt()->format('D jS F Y') : "?";},
            'Time' => function(Rehearsal $x){return $x->getStartsAt()? $x->getStartsAt()->format('g:ia') : "?";},
            'Where' => function(Rehearsal $x){return $x->getLocation() ? $x->getLocation()->getName() : "?";},
            'Title' => function(Rehearsal $x){return $x->getName();},
            'Comments' => function(Rehearsal $x){return $x->getComments();},
            'Last updated' => function(Rehearsal $x){return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();},
        ];
        return array("fields" => $fields, "entities" => $rehearsals);
    }

    protected function buildForm($form)
    {
        $form->add('starts_at', 'datetime12', [
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'dd/MM/yy',
        ]);
        $form->add('ends_at', new EndTimeType(), [
            'required' => false,
        ]);
        $form->add('location', 'entity', [
            'class' => 'IcsePublicBundle:Venue',
            'property' => 'name',
            'required' => false,
            'attr' => ['class' => 'entity-select'],
        ]);
        $form->add('name', 'text', [
            'required' => false,
            'label' => 'Title',
        ]);
        $form->add('comments', 'textarea', [
            'required' => false,
        ]);
    }
}
