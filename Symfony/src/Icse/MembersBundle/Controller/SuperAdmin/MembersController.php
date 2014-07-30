<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

use Icse\MembersBundle\Entity\Member;
use Icse\MembersBundle\Controller\Admin\EntityAdminController;
use Common\Tools; 

class MembersController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcseMembersBundle:Member');
    }

    protected function viewName()
    {
        return 'IcseMembersBundle:SuperAdmin:members.html.twig';
    }

    protected function newInstance()
    {
        return new Member();
    }

    protected function getTableContent()
    {
        $dm = $this->getDoctrine(); 
        $members = $dm->getRepository('IcseMembersBundle:Member')->findBy(array(), array('last_name'=>'asc'));

        $columns = array(
            array('heading' => 'ID', 'cell' => function(Member $member){return $member->getID();}),
            array('heading' => 'Name', 'cell' => function(Member $member){return $member->getFullName();}),
            array('heading' => 'Username', 'cell' => function(Member $member){return $member->getUsername();}),
            array('heading' => 'Email', 'cell' => function(Member $member){return $member->getEmail();}),
            array('heading' => 'Password', 'cell' => function(Member $member){return $member->getPassword()?"Stored":"Imperial";}),
            array('heading' => 'Active', 'cell' => function(Member $member){return $member->getActive()? "Yes":"No";}),
            array('heading' => 'Role', 'cell' => function(Member $member){return $member->getRole() == 100? "Super Admin":($member->getRole() == 10?"Admin":'('.strtolower($member->getRoles()[0]).')');}),
            array('heading' => 'Last Online', 'cell' => function(Member $member){return $member->getLastOnlineAt()? $this->timeagoDate($member->getLastOnlineAt()) : "Never";}),
        );
        return array("columns" => $columns, "entities" => $members, "serial_groups" => ['superadmin']);
    }

    protected function getForm($member)
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
                'choices' => array(1 => 'Auto', 10 => 'Admin', 100 => 'Super Admin')
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
                'invalid_message' => "Passwords must match",
                'first_options' => array('label' => 'New Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->getForm(); 
        return $form;
    }

    protected function putData($request, $member)
    {
        /* @var $member Member */
        $is_new_account = ($member->getID() === null);
        $mailer = $this->get('icse.mailer');
        $form = $this->getForm($member);
        $form->submit($request);

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
                    $form->addError(new FormError("Password must be at least 8 characters"));
                }
            } else {
                $form->addError(new FormError("Invalid password type"));
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

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid()) {
            $em->persist($member);
            $em->flush();

            if ($is_new_account) {
                $mailer->setTemplate('IcseMembersBundle:Email:account_created.html.twig')
                    ->setBodyFields([
                        'first_name' => $member->getFirstName(),
                        'username' => $member->getUsername(),
                        'email' => $member->getEmail(),
                        'password_type' => $password_type,
                        'plain_password' => $plain_password,
                    ])
                    ->setSubject('ICSE Online Account Created')
                    ->send($member->getEmail(), $member->getFirstName());
            } else if ($password_type == 'random') {
                $mailer->setTemplate('IcseMembersBundle:Email:temporary_password.html.twig')
                    ->setBodyFields([
                        'first_name' => $member->getFirstName(),
                        'username' => $member->getUsername(),
                        'email' => $member->getEmail(),
                        'plain_password' => $plain_password,
                    ])
                    ->setSubject('ICSE Account Password Reset')
                    ->send($member->getEmail(), $member->getFirstName());
            }

            return $this->get('ajax_response_gen')->returnSuccess();
        } else {
            if ($em->contains($member)) $em->refresh($member);
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

    private function generateMembersFromCSV(Request $request, File $csv_file)
    {
        $file_handle = fopen($csv_file->getPathname(), 'r');
        $csv_row = fgetcsv($file_handle);
        $line_number = 1;
        $next_csv_line_is_heading = true;
        if (!$csv_row)
        {
            return $this->get('ajax_response_gen')->returnFail("Nothing in file");
        }

        $dm = $this->getDoctrine();
        $member_repo = $dm->getRepository('IcseMembersBundle:Member');

        while ($csv_row)
        {
            if (count($csv_row) > 1) // is csv line
            {
                if ($next_csv_line_is_heading)
                {
                    $headings = array_flip($csv_row);
                    try {
                        $login_index = Tools::arrayGet($headings, 'Login');
                        $first_name_index = Tools::arrayGet($headings, 'First Name');
                        $last_name_index = Tools::arrayGet($headings, 'Last Name');
                        $email_index = Tools::arrayGet($headings, 'Email');
                    } catch (\UnexpectedValueException $e) {
                        return $this->get('ajax_response_gen')->returnFail("Line ".$line_number.": CSV headings not as expected.");
                    }
                    $next_csv_line_is_heading = false;
                }
                else // is data line
                {
                    if ($member_repo->isUnusedUsernameAndEmail($csv_row[$login_index], $csv_row[$email_index]))
                    {
                        try
                        {
                            $fakeRequestData = [
                                'form' => [
                                    'first_name' => $csv_row[$first_name_index],
                                    'last_name' => $csv_row[$last_name_index],
                                    'username' => $csv_row[$login_index],
                                    'email' => $csv_row[$email_index],
                                    'active' => '1',
                                    'role' => '1',
                                    'password_choice' => 'imperial',
                                    'plain_password' => [
                                        'first' => '',
                                        'second' => ''
                                    ],
                                    '_token' => Tools::arrayGet($request->request->get('form'), '_token')
                                ]
                            ];
                        }
                        catch (\UnexpectedValueException $e)
                        {
                            return $this->get('ajax_response_gen')->returnFail("CSV line parsing failed / No CSRF token.");
                        }
                        $fake_request = $request->duplicate(null, $fakeRequestData, null, null, array());
                        $member = new Member();
                        $return_response = $this->putData($fake_request, $member);
                        if (!$this->get('ajax_response_gen')->isSuccessResponse($return_response))
                        {
                            return $this->get('ajax_response_gen')->addErrorToResponse($return_response, "Error at line " . $line_number, true);
                        }
                    }
                }
            }
            else // not csv line
            {
                $next_csv_line_is_heading = true;
            }

            $csv_row = fgetcsv($file_handle);
            $line_number += 1;
        }

        return $this->get('ajax_response_gen')->returnSuccess();
    }

    public function createAction(Request $request)
    {
        $uploadedFiles = $request->files->get('form');
        if (isset($uploadedFiles['csv_file']))
        {
            return $this->generateMembersFromCSV($request, $uploadedFiles['csv_file']);
        }
        else
        {
            $member = new Member();
            return $this->putData($request, $member);
        }
    }

}
