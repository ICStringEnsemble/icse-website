<?php

namespace Icse\MembersBundle\Controller;

use Icse\MembersBundle\Entity\Member;
use Icse\MembersBundle\Entity\MemberProfile;
use Icse\MembersBundle\Validator\Constraints\UserPassword;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Common\Tools;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class AccountSettingsController extends Controller
{
    /**
     * @param Member $user
     * @return Form
     */
    private function getPasswordForm(Member $user)
    {
        return $this->get('form.factory')->createNamedBuilder('password', 'form',  $user)
            ->add('old_password', 'password', [
                'required' => true,
                'mapped' => false,
                'label' => 'Old Password',
                'constraints' => new UserPassword(["message" => "Incorrect password"]),
            ])
            ->add('password_operation', 'choice', [
                'choices' => [
                    Member::PASSWORD_IMPERIAL => "No, I'll use my Imperial College password",
                    Member::PASSWORD_SET => "Yes, make a separate password for ICSE"
                ],
                'label' => 'Create a new ICSE password?',
                'expanded' => true,
                'constraints' => new NotBlank()
            ])
            ->add('plain_password', 'repeated', [
                'type' => 'password',
                'required' => false,
                'invalid_message' => "Both passwords must match",
                'first_options' => ['label' => 'Choose a new password'],
                'second_options' => ['label' => 'Enter it again'],
            ])
            ->add('save', 'submit')
            ->getForm();
    }

    /**
     * @param Member $user
     * @return Form
     */
    private function getEmailAddrForm(Member $user)
    {
        return $this->get('form.factory')->createNamedBuilder('email_addr', 'form',  $user)
            ->add('email', 'email', [
                'required' => true,
                'label' => 'New email address',
                'data' => ''
            ])
            ->add('save', 'submit')
            ->getForm();
    }
     
    public function indexAction(Request $request)
    {
        /** @var Member $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $successes = [];

        $password_form = $this->getPasswordForm($user);
        $password_form->handleRequest($request);
        if ($password_form->isValid()) {
            if ($user->getPasswordOperation() == Member::PASSWORD_IMPERIAL) {
                $user->setSalt(null);
                $user->setPassword(null);
            }
            else if ($user->getPasswordOperation() == Member::PASSWORD_SET) {
                $user->setSalt(Tools::randString(40));
                $encoded = $this->container
                    ->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
            }
            $successes[] = "password";
            $password_form = $this->getPasswordForm(new Member);
        }

        $email_form = $this->getEmailAddrForm($user);
        $email_form->handleRequest($request);
        if ($email_form->isValid()) {
            $successes[] = "email";
            $email_form = $this->getEmailAddrForm(new Member);
        }

        $em = $this->getDoctrine()->getManager();
        if (count($successes)) $em->flush();
        else                   $em->refresh($user);
        
        return $this->render('IcseMembersBundle:AccountSettings:index.html.twig', [
            "user" => $user,
            "successes" => $successes,
            "password_form" => $password_form->createView(),
            "email_addr_form" => $email_form->createView()
        ]);
    }

    public function profileAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_HAVE_PROFILE'))
        {
            throw $this->createAccessDeniedException();
        }

        /** @var Member $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $profile = $user->getProfile();

        if (is_null($profile))
        {
            $profile = new MemberProfile();
            $profile->setMember($user);
        }

        $form = $this->createFormBuilder($profile)
            ->add('picture', 'entity', ['class' => 'IcsePublicBundle:Image', 'choice_label' => 'name', 'required' => false])
            ->add('instrument')
            ->add('join_year')
            ->add('study_subject', 'text', ['label' => 'Subject studied', 'required' => false])
            ->add('favourite_snack', 'text', ['label' => 'Favourite ICSE break snack', 'required' => false])
            ->add('memorable_moment', 'textarea', ['label' => 'Most memorable ICSE moment', 'required' => false])
            ->add('save', 'submit')
            ->getForm();

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            $em->persist($profile);
            $em->flush();
            return $this->redirect($this->generateUrl('IcseMembersBundle_my_profile'));
        }
        else
        {
            if ($em->contains($profile))
            {
                $em->refresh($profile);
            }
            return $this->render('IcseMembersBundle:AccountSettings:profile.html.twig', ['form' => $form->createView()]);
        }
    }
}
