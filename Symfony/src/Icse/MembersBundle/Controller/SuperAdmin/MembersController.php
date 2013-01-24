<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Icse\MembersBundle\Entity\Member; 


class MembersController extends Controller
{
    private function getTableContent()
    {
        $dm = $this->getDoctrine(); 
        $members = $dm->getRepository('IcseMembersBundle:Member')
                     ->findAll();

        $columns = array(
                            array('heading' => 'ID', 'cell' => function($member){return $member->getID();}),
                            array('heading' => 'Name', 'cell' => function($member){return $member->getFullName();}),
                            array('heading' => 'Username', 'cell' => function($member){return $member->getUsername();}),
                            array('heading' => 'Email', 'cell' => function($member){return $member->getEmail();}),
                            array('heading' => 'Password', 'cell' => function($member){return $member->getPassword()?"Stored":"Imperial";}),
                            array('heading' => 'Active', 'cell' => function($member){return $member->getActive()? "Yes":"No";}),
                            array('heading' => 'Role', 'cell' => function($member){return $member->getRole();}),
                        );
        return array("columns" => $columns, "entities" => $members);
    }

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
        $table_content = $this->getTableContent();
  
        return $this->render('IcseMembersBundle:SuperAdmin:members.html.twig', array('table_content' => $table_content,
                                                                                        'form' => $form->createView()
                                                                                    ));
    }

    public function tableAction()
    {
        $table_content = $this->getTableContent();
        return $this->render('IcseMembersBundle:Admin:table_fragment.html.twig', array('table_content' => $table_content)); 
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

            return new Response(json_encode("success"));
        } else {
            return new Response(json_encode("fail"));
        }
    }

    public function deleteAction($id) {
        $dm = $this->getDoctrine(); 
        try {
        $member = $dm->getRepository('IcseMembersBundle:Member')
            ->findOneById($id);
        } catch (\Doctrine\Orm\NoResultException $e) {
            return new Response(json_encode("fail"));
        } 
        $em = $dm->getManager();
        $em->remove($member);
        $em->flush();
        return new Response(json_encode("success"));
    }
}
