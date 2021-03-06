<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Component\Form\FormBuilder;
use Icse\PublicBundle\Entity\Subscriber;

class SignupListController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcsePublicBundle:Subscriber');
    }

    protected function getViewName()
    {
        return 'IcseMembersBundle:Admin:signup_list.html.twig';
    }

    protected function newInstance()
    {
        return new Subscriber();
    }

    protected function getListContent()
    {
        $dm = $this->getDoctrine(); 
        $subscribers = $dm->getRepository('IcsePublicBundle:Subscriber')->findAllThisYear();

        $fields = [
            'Email' => function($x){return $x->getEmail();},
            'Name' => function($x){return $x->getFullName();},
            'Login' => function($x){return $x->getLogin();},
            'Department' => function($x){return $x->getDepartment();},
            'Player?' => function($x){return $x->getPlayer() ? "Yes" : "No";},
            'Instrument' => function($x){return $x->getInstrument();},
            'Standard' => function($x){return $x->getStandard();},
            'Submitted at' => function($x){return $x->getSubscribedAt()->format('j/m/y H:i:s');},
        ];
        return ["fields" => $fields, "entities" => $subscribers];
    }

    protected function buildForm(FormBuilder $form)
    {}

    public function offlineViewerAction()
    {
        return $this->render('IcseMembersBundle:Admin:signup_list_offline.html.twig');
    }
}
