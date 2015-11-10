<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
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
            'Status' => function(Member $member){if (!$member->getActive()) return "Locked"; if (!$member->isAccountNonExpired()) return "Expired"; return "Active";},
            'Role' => function(Member $member){return $member->getRoleCode() == $member::ROLE_SUPER_ADMIN? "Super Admin":($member->getRoleCode() == $member::ROLE_ADMIN?"Admin":'('.strtolower($member->getRoles()[0]).')');},
            'Paid' => function(Member $member){return $member->getLastPaidMembershipOn()? $member->getLastPaidMembershipOn()->format('d/M/Y') : "Never";},
            'Last Online' => function(Member $member){return $member->getLastOnlineAt()? $this->timeagoDate($member->getLastOnlineAt()) : "Never";},
        ];
        return ["fields" => $fields, "entities" => $members, "serial_groups" => ['superadmin']];
    }

    protected function buildForm(FormBuilder $form)
    {
        $form->add('first_name', 'text');
        $form->add('last_name', 'text');
        $form->add('username', 'text');
        $form->add('email', 'email');
        $form->add('active', 'choice', [
            'choices' => [true => 'Yes', false => 'Locked'],
            'required' => true
        ]);
        $form->add('last_paid_membership_on', 'date', [
            'label' => 'Paid membership',
            'widget' => 'single_text',
            'required' => false,
            'format' => 'dd/MM/yy'
        ]);
        $form->add('role_code', 'choice', [
            'choices' => [Member::ROLE_AUTO => 'Auto', Member::ROLE_ADMIN => 'Admin', Member::ROLE_SUPER_ADMIN => 'Super Admin'],
            'required' => true,
            'label' => 'Role'
        ]);
        $form->add('password_operation', 'choice', [
            'choices' => [
                Member::PASSWORD_NO_CHANGE => "Don't Change",
                Member::PASSWORD_IMPERIAL => 'Imperial Password',
                Member::PASSWORD_RANDOM => 'Random Password',
                Member::PASSWORD_SET => 'Choose a Password'
            ],
            'label' => 'Password',
            'expanded' => true
        ]);
        $form->add('plain_password', 'repeated', [
            'type' => 'password',
            'required' => false,
            'invalid_message' => "Passwords must match",
            'first_options' => ['label' => 'New Password'],
            'second_options' => ['label' => 'Repeat Password'],
        ]);
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

    public function createAction(Request $request)
    {
        if (isset($request->files->get('form')['csv_file']))
        {
            return $this->handleBatchRequest($request);
        }
        return parent::createAction($request);
    }

    protected function preCheckFormValid(Form $form, $entity)
    {
        try
        {
            $this->applyPasswordOp($entity);
        }
        catch (\InvalidArgumentException $e)
        {
            $form->addError(new FormError($e->getMessage()));
        }
    }

    protected function postCreateEntity($member)
    {
        $this->get('icse.members_edit_hooks')->postCreateMember($member);
    }

    protected function postEditEntity($member)
    {
        $this->get('icse.members_edit_hooks')->postEditMember($member);
    }

    private function getBatchUploadForm()
    {
        return $this->createFormBuilder()
            ->add('csv_file', 'file', [
                'attr' => ['accept' => ".csv"]
            ])
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
                    $this->postCreateEntity($member);
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
