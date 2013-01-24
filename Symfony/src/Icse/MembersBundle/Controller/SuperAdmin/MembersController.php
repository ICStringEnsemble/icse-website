<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Icse\MembersBundle\Entity\Member; 


class MembersController extends Controller
{
    private function getForm($member)
    {
        $form = $this->createFormBuilder($member)
            ->add('first_name', 'text')
            ->add('last_name', 'text')
            ->add('username', 'text')
            ->add('email', 'email')
            ->add('active', 'choice', array(
                'choices' => array(true => 'Yes', false => 'No')
            ))
            ->add('role', 'choice', array(
                'choices' => array(1 => 'Normal', 10 => 'Admin', 100 => 'Super Admin')
            ))
            ->add('plain_password', 'repeated', array(
                'type' => 'password',
                'required' => false,
                'mapped' => false,
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->getForm(); 
        return $form;
    }

    public function indexAction()
    {
        $member = new Member();
        $form = $this->getForm($member);

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
  
        return $this->render('IcseMembersBundle:SuperAdmin:members.html.twig', array('members' => $members,
                                                                                        'table_columns' => $table_columns,
                                                                                        'form' => $form->createView()
                                                                                    ));
    }

    public function createAction(Request $request)
    {
        $member = new Member();
        $form = $this->getForm($member);
        $form->bind($request); 
        if ($form->isValid()) {
            $member->setCreatedAt(new \DateTime()); 
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();

            return new Response(json_encode("Success"));
        } else {
            return new Response(json_encode("Fail"));
        }
    }

    public function deleteAction($id) {
        $dm = $this->getDoctrine(); 
        try {
        $member = $dm->getRepository('IcseMembersBundle:Member')
            ->findOneById($id);
        } catch (\Doctrine\Orm\NoResultException $e) {
            return new Response(json_encode("Fail"));
        } 
        $em = $dm->getManager();
        $em->remove($member);
        $em->flush();
        return new Response(json_encode("Success"));
    }
}
