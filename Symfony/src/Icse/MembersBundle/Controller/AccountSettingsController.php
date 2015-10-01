<?php

namespace Icse\MembersBundle\Controller;

use Icse\MembersBundle\Entity\Member;
use Icse\MembersBundle\Entity\MemberProfile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Common\Tools;
use Symfony\Component\Validator\Constraints\Email;

class AccountSettingsController extends Controller
{

    private function isValidEmail($input)
    {
        $emailConstraint = new Email;
        $emailConstraint->checkMX = true;
        $errorList = $this->get('validator')->validateValue($input, $emailConstraint); 
        return count($errorList) == 0; 
    }
     
    public function indexAction(Request $request)
    {
        $cpResponse = [];
        $ceResponse = [];
        /** @var Member $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($request->request->get('form_id') == "cp") // if change password
        { 
            $encoder = $this->get('security.encoder_factory'); 
            if (!\Icse\MembersBundle\Security\Authentication\Provider\queryCredsValid($user, $request->request->get('old_password'), $encoder)) // if old password incorrect
            { 
                $cpResponse['oldpass'] = "Incorrect password";
            }
            elseif ($request->request->get('icse_passwd') == null)
            {
                $cpResponse['icsepass'] = "Please specify";
            }
            elseif ($request->request->get('icse_passwd') == 1)
            {
                $user->setPassword(null);
                $user->setSalt(null);
                $cpResponse['success'] = "Password was successfully changed";
            }
            elseif ($request->request->get('new_password') != $request->request->get('new_password_again'))
            {
                $cpResponse['newpass'] = "Passwords don't match";
            }
            elseif (strlen($request->request->get('new_password')) < 8)
            {
                $cpResponse['newpass'] = "Password must be at least 8 characters";
            }
            else
            {
                $user->setSalt(Tools::randString(40));
                $pass_hash = $encoder->getEncoder($user)->encodePassword($request->request->get('new_password'), $user->getSalt());
                $user->setPassword($pass_hash);
                $cpResponse['success'] = "Password was sucessfully changed";
            }

            $em = $this->getDoctrine()->getManager();
            if (isset($cpResponse['success']))
            {
                $em->flush();
            }
            else
            {
                $em->refresh($user);
                $cpResponse['passtype'] = $request->request->get('icse_passwd');
            }
        }
        
        else if ($request->request->get('form_id') == "ce")  // change email
        {
            if ($this->isValidEmail($request->request->get('new_email')))
            {
                $user->setEmail($request->request->get('new_email'));
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $ceResponse['success'] = "Email address was successfully changed";
            }
            else
            {
                $ceResponse['newemail'] = "Invalid email address";
            }
        }

        $imperial_password = !($user->getPassword());
        $email = $user->getEmail();
        return $this->render('IcseMembersBundle:AccountSettings:index.html.twig', array("ImperialPasswd" => $imperial_password,
                                                                                        "email" => $email, 
                                                                                        "ceResponse" => $ceResponse, 
                                                                                        "cpResponse" => $cpResponse));
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
