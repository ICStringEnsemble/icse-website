<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

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
            array('heading' => 'Last Online', 'cell' => function($member){return $member->getLastOnlineAt()? $member->getLastOnlineAt()->format('Y-m-d H:i:s') : "Never";}),
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
        $is_new_account = ($member->getID() === null);
        $mailer = $this->get('icse_mailer'); 
        $form = $this->getForm($member);
        $form->bind($request);

        $password_type = $form->get('password_choice')->getData();
        $plain_password = '';
        
        if ($password_type == 'no_change') {
        
        } else if ($password_type == 'imperial') {
            $member->setSalt(null);
            $member->setPassword(null);
        } else { // set a password
            if ($password_type == 'random') {
                $plain_password = Tools::randString(15);
            } else if ($password_type == 'set') {
                $plain_password = $form->get('plain_password')->getData();
                if (strlen($plain_password) < 8) {
                    $form->addError(new FormError("Password must be at least 8 characters."));
                }
            } else {
                $form->addError(new FormError("Invalid password type."));
            }

            if ($form->isValid()) {
                $member->setSalt(Tools::randString(40));
                $encoder = $this->get('security.encoder_factory');  
                $pass_hash = $encoder->getEncoder($member)->encodePassword($plain_password, $member->getSalt());
                $member->setPassword($pass_hash);
            }
 
        }

        if ($is_new_account) {
            $member->setCreatedAt(new \DateTime()); 
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();

            if ($is_new_account) {
                $mailer->send(array(
                    'template' => 'IcseMembersBundle:Email:account_created.html.twig',
                    'template_params' => array(
                            'first_name' => $member->getFirstName(),
                            'username' => $member->getUsername(),
                            'email' => $member->getEmail(),
                            'password_type' => $password_type,
                            'plain_password' => $plain_password,
                    ),
                    'subject' => 'ICSE Online Account Created', 
                    'to' => $member->getEmail()
                )); 
            } else if ($password_type == 'random') {
                $mailer->send(array(
                    'template' => 'IcseMembersBundle:Email:temporary_password.html.twig',
                    'template_params' => array(
                            'first_name' => $member->getFirstName(),
                            'username' => $member->getUsername(),
                            'email' => $member->getEmail(),
                            'plain_password' => $plain_password,
                    ),
                    'subject' => 'ICSE Account Password Reset', 
                    'to' => $member->getEmail()
                ));            
            }

            return $this->get('ajax_response_gen')->returnSuccess();
        } else {
            return $this->get('ajax_response_gen')->returnFail($form);
        }  
    }

    public function indexAction()
    {
        $member = new Member();
        $form = $this->getForm($member);
        $table_content = $this->getTableContent();

        $csv_form = $this->createFormBuilder()
                    ->add('csv_file', 'file', array('label' => 'CSV File'))
                    ->getForm(); 
  
        return $this->render('IcseMembersBundle:SuperAdmin:members.html.twig', array('table_content' => $table_content,
                                                                                    'form' => $form->createView(),
                                                                                    'csv_form' => $csv_form->createView()
                                                                                    ));
    }

    public function tableAction()
    {
        $table_content = $this->getTableContent();
        return $this->render('IcseMembersBundle:Admin:table_fragment.html.twig', array('table_content' => $table_content)); 
    }

    public function createAction(Request $request)
    {
        $uploadedFiles = $request->files->get('form');
        if (isset($uploadedFiles['csv_file'])) {
            $csv_file = $uploadedFiles['csv_file'];
            $file_handle = fopen($csv_file->getPathname(), 'r');
            $csv_row = fgetcsv($file_handle);
            if ($csv_row) {
                $headings = array_flip($csv_row);
                try {
                    $login_index = Tools::arrayGet($headings, 'Login');
                    $first_name_index = Tools::arrayGet($headings, 'First Name');
                    $last_name_index = Tools::arrayGet($headings, 'Last Name');
                    $email_index = Tools::arrayGet($headings, 'Email');
                } catch (\UnexpectedValueException $e) {
                    return $this->get('ajax_response_gen')->returnFail("CSV headings not as expected.");
                }
                $csv_row = fgetcsv($file_handle);
                $line_number = 2;
                $dm = $this->getDoctrine();
                while ($csv_row) {
                    if ($dm->getRepository('IcseMembersBundle:Member')
                           ->isUnusedUsernameAndEmail($csv_row[$login_index], $csv_row[$email_index])) {
                        try {
                            $fakeRequestData = array(
                                'form' => array(
                                    'first_name' => $csv_row[$first_name_index],
                                    'last_name' => $csv_row[$last_name_index],
                                    'username' => $csv_row[$login_index],
                                    'email' => $csv_row[$email_index],
                                    'active' => '1',
                                    'role' => '1',
                                    'password_choice' => 'imperial',
                                    'plain_password' => array(
                                        'first' => '',
                                        'second' => ''
                                    ),
                                    '_token' => Tools::arrayGet($request->request->get('form'), '_token')
                                )
                            ); 
                        } catch (\UnexpectedValueException $e) {
                            return $this->get('ajax_response_gen')->returnFail("No CSRF token.");
                        }
                        $fakeRequest = $request->duplicate(null, $fakeRequestData, null, null, array());
                        $member = new Member();
                        $return_response = $this->putData($fakeRequest, $member);
                        if (!$this->get('ajax_response_gen')->isSuccessResponse($return_response)) {
                            return $this->get('ajax_response_gen')->addErrorToResponse($return_response, "Error at line " . $line_number, true);
                        }
                    }
                    $csv_row = fgetcsv($file_handle);
                    $line_number += 1;
                }
                return $this->get('ajax_response_gen')->returnSuccess();
            } else {
                return $this->get('ajax_response_gen')->returnFail("Nothing in file");
            }

        } else {
            $member = new Member();
            return $this->putData($request, $member);
        }
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
        if ($member) {
            $em = $dm->getManager();
            $em->remove($member);
            $em->flush();
        }
        return $this->get('ajax_response_gen')->returnSuccess();
    }
}
