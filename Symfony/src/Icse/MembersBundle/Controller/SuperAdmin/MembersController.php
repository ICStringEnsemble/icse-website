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
            'Role' => function(Member $member){return $member->getRole() == $member::ROLE_SUPER_ADMIN? "Super Admin":($member->getRole() == $member::ROLE_ADMIN?"Admin":'('.strtolower($member->getRoles()[0]).')');},
            'Paid' => function(Member $member){return $member->getLastPaidMembershipOn()? $member->getLastPaidMembershipOn()->format('d/M/Y') : "Never";},
            'Last Online' => function(Member $member){return $member->getLastOnlineAt()? $this->timeagoDate($member->getLastOnlineAt()) : "Never";},
        ];
        return ["fields" => $fields, "entities" => $members, "serial_groups" => ['superadmin']];
    }

    /**
     * @param Member $member
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm($member)
    {
        $form = $this->createFormBuilder($member)
            ->setMethod($member->getID() === null ? 'POST' : 'PUT')
            ->add('first_name', 'text')
            ->add('last_name', 'text')
            ->add('username', 'text')
            ->add('email', 'email')
            ->add('active', 'choice', [
                'choices' => [true => 'Yes', false => 'No']
            ])
            ->add('last_paid_membership_on', 'date', [
                'label' => 'Paid membership',
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yy'
            ])
            ->add('role', 'choice', [
                'choices' => [$member::ROLE_AUTO => 'Auto', $member::ROLE_ADMIN => 'Admin', $member::ROLE_SUPER_ADMIN => 'Super Admin']
            ])
            ->add('password_operation', 'choice', [
                'choices' => [
                    Member::PASSWORD_NO_CHANGE => "Don't Change",
                    Member::PASSWORD_IMPERIAL => 'Imperial Password',
                    Member::PASSWORD_RANDOM => 'Random Password',
                    Member::PASSWORD_SET => 'Choose a Password'
                ],
                'label' => 'Password',
                'expanded' => true
            ])
            ->add('plain_password', 'repeated', [
                'type' => 'password',
                'required' => false,
                'invalid_message' => "Passwords must match",
                'first_options' => ['label' => 'New Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->getForm(); 
        return $form;
    }

    /**
     * @param Member $member
     */
    private function sendNewAccountEmail($member)
    {
        $mailer = $this->get('icse.mailer');
        $mailer->setTemplate('IcseMembersBundle:Email:account_created.html.twig')
            ->setBodyFields(['member' => $member])
            ->setSubject('ICSE Online Account Created')
            ->send($member->getEmail(), $member->getFirstName());
    }

    /**
     * @param Member $member
     */
    private function sendTempPasswordEmail($member)
    {
        $mailer = $this->get('icse.mailer');
        $mailer->setTemplate('IcseMembersBundle:Email:temporary_password.html.twig')
            ->setBodyFields(['member' => $member])
            ->setSubject('ICSE Account Password Reset')
            ->send($member->getEmail(), $member->getFirstName());
    }

    /**
     * @param Member $member
     */
    private function applyPasswordOp($member)
    {
        $op = $member->getPasswordOperation();

        if ($op == Member::PASSWORD_NO_CHANGE) return;

        if ($op == Member::PASSWORD_IMPERIAL)
        {
            $member->setPassword(null);
            $member->setSalt(null);
            return;
        }

        if ($op == Member::PASSWORD_RANDOM)
        {
            $member->setPlainPassword(Tools::randString(15));
        }
        else if ($op == Member::PASSWORD_SET)
        {
            if (strlen($member->getPlainPassword()) < 8)
            {
                throw new \InvalidArgumentException("Password must be at least 8 characters");
            }
        }
        else
        {
            throw new \InvalidArgumentException("Invalid password type");
        }

        $member->setSalt(Tools::randString(40));
        $encoder = $this->get('security.encoder_factory')->getEncoder($member);
        $pass_hash = $encoder->encodePassword($member->getPlainPassword(), $member->getSalt());
        $member->setPassword($pass_hash);
    }

    private function handleBatchRequest($request)
    {
        $form = $this->getBatchUploadForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $file = $form->getData()['csv_file'];
            return $this->updateMembersFromCSV($file);
        }
        else
        {
            return $this->get('ajax_response_gen')->returnFail($form);
        }
    }

    protected function putData($request, $member)
    {
        if (isset($request->files->get('form')['csv_file']))
        {
            return $this->handleBatchRequest($request);
        }

        /* @var $member Member */
        $is_new_account = ($member->getID() === null);
        $form = $this->getForm($member);
        $form->handleRequest($request);

        try
        {
            $this->applyPasswordOp($member);
        }
        catch (\InvalidArgumentException $e)
        {
            $form->addError(new FormError($e->getMessage()));
        }

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            $em->persist($member);
            $em->flush();

            if ($is_new_account)
            {
                $this->postAddNewMember($member);
            }
            else if ($member->getPasswordOperation() == Member::PASSWORD_RANDOM)
            {
                $this->sendTempPasswordEmail($member);
            }

            return $this->get('ajax_response_gen')->returnSuccess();
        }
        else
        {
            if ($em->contains($member)) $em->refresh($member);
            return $this->get('ajax_response_gen')->returnFail($form);
        }  
    }

    private function postAddNewMember($member)
    {
        $this->sendNewAccountEmail($member);
    }

    private function getBatchUploadForm()
    {
        return $this->createFormBuilder()
            ->add('csv_file', 'file')
            ->getForm();
    }

    protected function indexData()
    {
        return [
            'csv_form' => $this->getBatchUploadForm()->createView()
        ];
    }

    private function updateMembersFromCSV(File $csv_file)
    {
        $parser = $this->get('icse.members_report_parser');
        $em = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository('IcseMembersBundle:Member');

        try
        {
            foreach ($parser->generateMembersFromCSV($csv_file->getPathname()) as $generated_member)
            {
                /** @var Member $generated_member */
                /** @var Member $member */
                $member = $repo->findOneByUsername($generated_member->getUsername());
                if ($member === null) $member = $repo->findOneByEmail($generated_member->getEmail());
                if ($member === null)
                {
                    $member = $generated_member;

                    $errors = $this->get('validator')->validate($member);
                    if (count($errors) > 0) throw new \Exception((string)$errors);

                    $em->persist($member);
                    $em->flush();
                    $this->postAddNewMember($member);
                }
                else
                {
                    $member->setLastPaidMembershipOn(max($member->getLastPaidMembershipOn(), $generated_member->getLastPaidMembershipOn()));
                    $member->setActive(true);
                }
            }
        }
        catch (\Exception $e)
        {
            return $this->get('ajax_response_gen')->returnFail($e->getMessage());
        }

        $em->flush();
        return $this->get('ajax_response_gen')->returnSuccess();
    }
}
