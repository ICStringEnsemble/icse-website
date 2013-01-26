<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Icse\MembersBundle\Entity\Member; 
use Common\Tools; 

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
            array('heading' => 'Role', 'cell' => function($member){return $member->getRole() == 100? "Super Admin":($member->getRole() == 10?"Admin":"User");}),
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
                'choices' => array(1 => 'User', 10 => 'Admin', 100 => 'Super Admin')
            ))
            ->add('password_choice', 'choice', array(
                'choices' => array('no_change' => 'Don\'t Change', 'imperial' => 'Imperial Password', 'random' => 'Random Password', 'set' => 'Choose a Password'),
                'label' => 'Password',
                'expanded' => true,
                'mapped' => false
            ))
            ->add('plain_password', 'repeated', array(
                'type' => 'password',
                'required' => false,
                'mapped' => false,
                'first_options' => array('label' => 'New Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->getForm(); 
        return $form;
    }

    private function putData($request, $member)
    {
        $other_errors = false;
        $form = $this->getForm($member);
        $form->bind($request);

        $password_type = $form->get('password_choice')->getData();
        
        if ($password_type == 'no_change') {
        
        } else if ($password_type == 'imperial') {
            $member->setSalt(null);
            $member->setPassword(null);
        } else { // set a password
            if ($password_type == 'random') {
                $plain_password = Tools::randString(10);
            } else if ($password_type == 'set') {
                $plain_password = $form->get('plain_password')->getData();
                if (strlen($plain_password) < 8) {
                    $other_errors = true;
                }
            } else {
                $other_errors = true;
            }

            if (!$other_errors) {
                $member->setSalt(Tools::randString(40));
                $encoder = $this->get('security.encoder_factory');  
                $pass_hash = $encoder->getEncoder($member)->encodePassword($plain_password, $member->getSalt()); 
                $member->setPassword($pass_hash);
            }
 
        }

        if ($form->isValid() && !$other_errors) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();
            return new Response(json_encode("success"));
        } else {
            return new Response(json_encode("fail"));
        }  
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
        $member->setCreatedAt(new \DateTime()); 
        return $this->putData($request, $member);
    }

    public function updateAction(Request $request, $id)
    {
        $dm = $this->getDoctrine(); 
        $member = $dm->getRepository('IcseMembersBundle:Member')->findOneById($id);
        if (!$member) {
            throw $this->createNotFoundException('Entity does not exist'); 
        }
        return $this->putData($request, $member);
    }

    public function deleteAction($id) {
        $dm = $this->getDoctrine(); 
        $member = $dm->getRepository('IcseMembersBundle:Member')->findOneById($id);
        if (!$member) {
            //throw $this->createNotFoundException('Entity does not exist'); 
            return new Response(json_encode("success"));
        }
        $em = $dm->getManager();
        $em->remove($member);
        $em->flush();
        return new Response(json_encode("success"));
    }
}
