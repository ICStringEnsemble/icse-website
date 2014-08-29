<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Icse\PublicBundle\Entity\Subscriber;


if (!function_exists('ldap_get_info')) { // If in testing environment
    function get_ldap_email($login)
    {
        if ($login == "js10")
        {
            return "john.smith10@imperial.ac.uk";
        }
        else
        {
            return false;
        }
    }

    function ldap_get_names($login)
    {
        return array("John", "Smith");
    }

    function ldap_get_info($login)
    {
        if ($login == "js10")
        {
            return array(2 => "Department of Wibble");
        }
        else
        {
            return false;
        }
    }
} else { // If in college
    function get_ldap_email($login)
    {
        $ldap = ldap_connect('addressbook.imperial.ac.uk');
        $search_result = ldap_search($ldap, 'o=Imperial College,c=GB', 'uid=' . $login);
        $entry = ldap_first_entry($ldap, $search_result);
        if ($entry) {
                $attrs = ldap_get_attributes($ldap, $entry);
                return $attrs['mail'][0];
        } else {
                return false;
        } 
    }
}

class SignUpController extends Controller
{
    private function getSiteText($name)
    {
        $textObject = $this->getDoctrine()
                                ->getRepository('IcsePublicBundle:SiteSection')
                                ->findOneByName($name);

        return $textObject ? $textObject->getText() : "";
    }

    public function joinAction(Request $request, $_route, $freshers = false)
    {
        $is_success_page = $request->query->has('ss') && $request->query->has('sn');
        if ($is_success_page)
        {
            return $this->render('IcsePublicBundle:SignUp:success.html.twig', [
                'success_status' => $request->query->get('ss'),
                'person_name' => $request->query->get('sn'),
                'freshersfair' => $freshers
            ]);
        }

        $subscriber = new Subscriber();
        $form = $this->createFormBuilder($subscriber)
            ->add('first_name', 'text')
            ->add('last_name', 'text')
            ->add('email', 'email')
            ->add('login', 'text', ['required' => false])
            ->add('department', 'text', ['required' => false])
            ->add('player', 'choice', [
                'choices' => [
                    true => 'Yes! I play an instrument and would like to join in.',
                    false => 'Nope, but notify me about any concerts!'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Are you interested in joining ICSE?'
            ])
            ->add('instrument', 'choice', [
                'choices' => [
                    'Violin' => 'Violin',
                    'Viola' => 'Viola',
                    'Cello' => 'Cello',
                    'Double Bass' => 'Double Bass',
                    'other' => 'Other'
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => false
            ])
            ->add('other_instrument', 'text', [
                'required' => false,
                'attr' => ['placeholder' => 'Please specify']
            ])
            ->add('standard', 'text', [
                'label' => 'Give us an idea of your standard',
                'required' => false
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid())
        {
            if ($subscriber->getLogin())
            {
                $info = ldap_get_info($subscriber->getLogin());
                if ($info && !$subscriber->getDepartment()) $subscriber->setDepartment($info[2]);
            }
            else
            {
                $subscriber->setDepartment(null);
            }

            $subscriber->setSubscribedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($subscriber);
            $em->flush();

            /* Add to Mailman */
            $success_status = "success";
            if(!$this->get('kernel')->isDebug())
            {
                $mailman = $this->get($subscriber->isPlayer() ? 'icsemembers_mailman' : 'icsepublic_mailman');
                if (!$mailman->subscribe($subscriber->getEmail()))
                {
                    if ($mailman->error == 'already') $success_status = 'already';
                    else $success_status = 'mailmanfail';
                }
            }

            return $this->redirect($this->generateUrl($_route, [
                'ss' => $success_status,
                'sn' => $subscriber->getFirstName()
            ]));
        }

        $username_or_email = $subscriber->getLogin() ? $subscriber->getLogin() : $subscriber->getEmail();

        $slideshow_images = [];
        if ($freshers && $request->isMethod('GET'))
        {
            $slideshow_images = $this->getDoctrine()->getRepository('IcsePublicBundle:Image')->findSlideshowImages();
        }

        return $this->render('IcsePublicBundle:SignUp:join.html.twig', [
            'join_intro' => $this->getSiteText('join_intro'),
            'form' => $form->createView(),
            'username_or_email' => $username_or_email,
            'freshersfair' => $freshers,
            'slideshow_images' => $slideshow_images
        ]);
    }

    private function isValidEmail($input)
    {
        $emailConstraint = new \Symfony\Component\Validator\Constraints\Email;
        $emailConstraint->checkMX = true;
        $errorList = $this->get('validator')->validateValue($input, $emailConstraint); 
        return count($errorList) == 0; 
    }

    public function query_usernameAction(Request $request)
    {
        $input = $request->query->get('input');
        $output = array();
        if ($input != "" && $this->isValidEmail($input))
        {
            $output['type'] = 'email';
        }
        else
        {
            $ldap_info = ldap_get_info($input);
            if ($ldap_info == false)
            {
                $output['type'] = 'invalid';
            }
            else
            {
                $output['type'] = 'username';
                $attempts = 0;
                do
                {
                    $email = get_ldap_email($input);
                    $attempts += 1;
                } while ($email == false && $attempts < 10);
                if ($email == false) $email = "";
                $output['email'] = $email;
                $attempts = 0;
                do
                {
                    $names = ldap_get_names($input);
                    $attempts += 1;
                } while ($names == false && $attempts < 10);
                if ($names == false) $names = array("","");
                $output['first_name'] = $names[0];
                $output['last_name'] = $names[count($names)-1];
                $output['department'] = $ldap_info[2];
            }
        }
        return new Response(json_encode($output));
    }
}
