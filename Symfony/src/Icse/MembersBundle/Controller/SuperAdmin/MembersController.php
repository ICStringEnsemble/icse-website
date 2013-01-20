<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Icse\MembersBundle\Entity\Member; 


class MembersController extends Controller
{
    public function indexAction()
    {
        $dm = $this->getDoctrine(); 
        $members = $dm->getRepository('IcseMembersBundle:Member')
                     ->findAll();

        $table_columns = array(
                            array('heading' => 'ID', 'cell' => function($member){return $member->getID();}),
                            array('heading' => 'Name', 'cell' => function($member){return $member->getFullName();}),
                            array('heading' => 'Username', 'cell' => function($member){return $member->getUsername();}),
                            array('heading' => 'Email', 'cell' => function($member){return $member->getEmail();}),
                            array('heading' => 'Password', 'cell' => function($member){return $member->getPassword()?"Stored":"Imperial";}),
                            array('heading' => 'Active', 'cell' => function($member){return $member->getActive()? "Yes":"No";}),
                            array('heading' => 'Role', 'cell' => function($member){return $member->getRole();}),
                        );
  
        $form = $this->createFormBuilder()
            ->add('first_name', 'text')
            ->add('last_name', 'text')
            ->add('username', 'text')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('email', 'email')
            ->getForm(); 

        return $this->render('IcseMembersBundle:SuperAdmin:members.html.twig', array('members' => $members,
                                                                                        'table_columns' => $table_columns,
                                                                                        'form' => $form->createView()
                                                                                    ));
    }

    public function createAction()
    {
        return new Response("Add new member");
    }
}
