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

    public function joinAction(Request $request, $_route)
    {
        $freshers = $request->query->has('freshersfair');
        $is_success_page = $request->query->has('sn');

        if ($is_success_page)
        {
            if ($request->query->has('already'))
            {
                $page_body = "<p>Thanks " .  $request->query->get('sn') . ". You're already on our mailing list, so we should be in touch shortly.</p>
                <p>If for some reason you are not receiving messages from us, please e-mail us at icse@imperial.ac.uk, and we'll try and put things right.</p>";
            }
            else if ($request->query->has('mailmanfail'))
            {
                $page_body = "<p>Unfortunately, for some reason we were unable to add you to our mailing list.
                Please e-mail us at icse@imperial.ac.uk, and we'll try and put things right.</p>";
            }
            else
            {
                $page_body = "<p>Thanks " .  $request->query->get('sn') . ", we'll be in touch shortly.</p>";
            }

            $parameters = array('pageId' => 'join',
                                'pageTitle' => 'Join Us',
                                'pageBody' => $page_body,
                                'no_nav' => $freshers);
            if ($freshers)
            {
                $parameters['reloadPeriod'] = 3000;
                $parameters['reloadPath'] = $this->generateUrl($_route, ['freshersfair' => 1]);
            }
            return $this->render('IcsePublicBundle:Default:generic_page.html.twig', $parameters);
        }
        $username_or_email = "";
        $subscriber = new Subscriber();
        $form = $this->createFormBuilder($subscriber)
                     ->add('first_name', 'text')
                     ->add('last_name', 'text')
                     ->add('email', 'email')
                     ->add('login', 'text', array('required' => false))
                     ->add('department', 'text', array('required' => false))
                     ->add('player', 'choice', array(
                                     'choices' => array(
                                         true => 'Yes! I play an instrument and would like to join in.',
                                         false => 'Nope, but notify me about any concerts!'
                                     ),
                                     'expanded' => true,
                                     'multiple' => false,
                                     'label' => 'Are you interested in joining ICSE?'
                                 ))
                     ->add('instrument', 'choice', array(
                                     'choices' => array(
                                         'Violin' => 'Violin',
                                         'Viola' => 'Viola',
                                         'Cello' => 'Cello',
                                         'Double Bass' => 'Double Bass',
                                         'other' => 'Other'
                                     ), 
                                     'expanded' => true,
                                     'multiple' => true,
                                     'required' => false
                                 ))
                     ->add('other_instrument', 'text', array('label' => ' ', 'required' => false, 'attr' => array('placeholder' => 'Please specify')))
                     ->add('standard', 'text', array('label' => 'Give us an idea of your standard', 'required' => false))
                     ->getForm(); 

        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            /* Do more error checking, and fill in implied data */
            if ($subscriber->getLogin())
                {
                    $info = ldap_get_info($subscriber->getLogin());
                    if (!$info)                               $form->addError(new \Symfony\Component\Form\FormError("Not a valid Imperial login username."));
                    else if (!$subscriber->getDepartment())   $subscriber->setDepartment($info[2]);
                }
            else
                {
                    $subscriber->setDepartment(null);
                }
            if ($subscriber->isPlayer())
            {
                $indexOtherInInstruments = array_search('other', $subscriber->getInstrument());
                $selectedOther = ($indexOtherInInstruments !== false);
                if (!$subscriber->getInstrument() || ($selectedOther && !$subscriber->getOtherInstrument()))
                {
                    $form->addError(new \Symfony\Component\Form\FormError("Please specify the instrument you play."));
                }
                if ($selectedOther)
                {
                    $instruments = $subscriber->getInstrument();
                    $instruments[$indexOtherInInstruments] = $subscriber->getOtherInstrument();
                    $subscriber->setInstrument($instruments);
                }
                $instrumentString = implode (', ', $subscriber->getInstrument());
                $subscriber->setInstrument($instrumentString);
                if (!$subscriber->getStandard())
                {
                    $form->addError(new \Symfony\Component\Form\FormError("Please indicate your playing standard."));
                }
            }
            else
            {
                $subscriber->setInstrument(null);
                $subscriber->setStandard(null);
            }
            /* End error checking */
            if ($form->isValid())
            {
                $subscriber->setSubscribedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($subscriber);
                $em->flush();

                $already_on_mailman = false;
                $mailman_failure = false;

                /* Add to Mailman */
                if(!$this->get('kernel')->isDebug())
                {
                    if ($subscriber->isPlayer())
                    {
                        $mailman = $this->get('icsemembers_mailman');
                    }
                    else
                    {
                        $mailman = $this->get('icsepublic_mailman');
                    } 
                    if (!$mailman->subscribe($subscriber->getEmail()))
                    {
                        if ($mailman->error == 'already') $already_on_mailman = true;
                        else $mailman_failure = true;
                    }
                }

                $query_array = ['sn' => $subscriber->getFirstName()];
                if ($freshers) $query_array['freshersfair'] = 1;
                if ($already_on_mailman) $query_array['already'] = 1;
                if ($mailman_failure) $query_array['mailmanfail'] = 1;
                return $this->redirect($this->generateUrl('IcsePublicBundle_join', $query_array));
            }
            else
            {
                if ($subscriber->getLogin())
                    $username_or_email = $subscriber->getLogin();
                else
                    $username_or_email = $subscriber->getEmail();
            }
        }
        return $this->render('IcsePublicBundle:SignUp:join.html.twig', array('join_intro' => $this->getSiteText('join_intro'),
                                                                             'form' => $form->createView(),
                                                                             'username_or_email' => $username_or_email,
                                                                             'no_nav' => $freshers));
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

    public function freshersfairAction()
    {
        return $this->render('IcsePublicBundle:SignUp:freshersfair.html.twig');
    }
}
