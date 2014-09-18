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

    protected function getViewName()
    {
        return 'IcseMembersBundle:SuperAdmin:members.html.twig';
    }

    protected function newInstance()
    {
        return new Member();
    }

    protected function getListContent()
    {
        $dm = $this->getDoctrine(); 
        $members = $dm->getRepository('IcseMembersBundle:Member')->findBy(array(), array('last_name'=>'asc'));

        $fields = [
            'ID' => function(Member $member){return $member->getID();},
            'Name' => function(Member $member){return $member->getFullName();},
            'Username' => function(Member $member){return $member->getUsername();},
            'Email' => function(Member $member){return $member->getEmail();},
            'Password' => function(Member $member){return $member->getPassword()?"Stored":"Imperial";},
            'Active' => function(Member $member){return $member->getActive()? "Yes":"No";},
            'Role' => function(Member $member){return $member->getRole() == 100? "Super Admin":($member->getRole() == 10?"Admin":'('.strtolower($member->getRoles()[0]).')');},
            'Paid' => function(Member $member){return $member->getLastPaidMembershipOn()? $member->getLastPaidMembershipOn()->format('d/M/Y') : "Never";},
            'Last Online' => function(Member $member){return $member->getLastOnlineAt()? $this->timeagoDate($member->getLastOnlineAt()) : "Never";},
        ];
        return ["fields" => $fields, "entities" => $members, "serial_groups" => ['superadmin']];
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
            ->add('last_paid_membership_on', 'date', array(
                'label' => 'Paid membership',
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yy'
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

    private function sendNewAccountEmail($member, $password_type='imperial', $plain_password='')
    {
        $mailer = $this->get('icse.mailer');
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
    }

    private function sendTempPasswordEmail($member, $plain_password='')
    {
        $mailer = $this->get('icse.mailer');
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

    private function isValidEmail($input)
    {
        $emailConstraint = new \Symfony\Component\Validator\Constraints\Email;
        $emailConstraint->checkMX = true;
        $errorList = $this->get('validator')->validateValue($input, $emailConstraint);
        return count($errorList) == 0;
    }

    protected function putData($request, $member)
    {
        /* @var $member Member */
        $is_new_account = ($member->getID() === null);
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

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            $em->persist($member);
            $em->flush();

            if ($is_new_account)
            {
                $this->sendNewAccountEmail($member, $password_type, $plain_password);
            }
            else if ($password_type == 'random')
            {
                $this->sendTempPasswordEmail($member, $plain_password);
            }

            return $this->get('ajax_response_gen')->returnSuccess();
        } else {
            if ($em->contains($member)) $em->refresh($member);
            return $this->get('ajax_response_gen')->returnFail($form);
        }  
    }

    private function getBatchUploadForm()
    {
        return $this->createFormBuilder()
            ->add('csv_file', 'file', array('label' => 'CSV File'))
            ->getForm();
    }

    protected function indexData()
    {
        return [
            'csv_form' => $this->getBatchUploadForm()->createView()
        ];
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
        $em = $dm->getManager();
        $member_repo = $dm->getRepository('IcseMembersBundle:Member');

        while ($csv_row)
        {
            if (count($csv_row) > 1) // is csv line
            {
                if ($next_csv_line_is_heading)
                {
                    $headings = array_flip($csv_row);
                    try {
                        $date_index = Tools::arrayGet($headings, 'Date');
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
                    $member = $member_repo->findOneBy(['username' => $csv_row[$login_index]]);
                    if ($member === null) $member = $member_repo->findOneBy(['email' => $csv_row[$email_index]]);
                    if ($member === null)
                    {
                        $member = new Member();
                        $is_new = true;
                    }
                    else
                    {
                        $is_new = false;
                    }

                    try
                    {
                        if ($is_new)
                        {
                            $member->setFirstName($csv_row[$first_name_index]);
                            $member->setLastName($csv_row[$last_name_index]);
                            $member->setUsername($csv_row[$login_index]);
                            $member->setEmail($csv_row[$email_index]);
                            $member->setRole(1);
                            $member->setSalt(null);
                            $member->setPassword(null);

                            if (!$member->getFirstName() or !$member->getLastName() or !$member->getUsername() or !$this->isValidEmail($member->getEmail()))
                            {
                                throw new \UnexpectedValueException("Field empty or invalid");
                            }
                        }

                        if (strlen($csv_row[$date_index]) != 10) throw new \UnexpectedValueException("Unexpected date format " . strlen($csv_row[$date_index]));

                        $new_payment_date = \DateTime::createFromFormat('d/m/Y', $csv_row[$date_index]);
                        $member->setLastPaidMembershipOn(max($member->getLastPaidMembershipOn(), $new_payment_date));
                        $member->setActive(true);
                    }
                    catch (\UnexpectedValueException $e)
                    {
                        return $this->get('ajax_response_gen')->returnFail("Error at line " . $line_number . " " . $e->getMessage());
                    }

                    if ($is_new)
                    {
                        $em->persist($member);
                        $em->flush();
                        $this->sendNewAccountEmail($member, 'imperial');
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

        $em->flush();
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
