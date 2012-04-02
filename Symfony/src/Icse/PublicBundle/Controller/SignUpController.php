<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Icse\PublicBundle\Entity\Subscriber;


if (!function_exists('ldap_get_info'))
  {
    function ldap_get_mail($login)
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
  }

class SignUpController extends Controller
{
  private function getSiteText($name)
    {
      $textObject = $this->getDoctrine()
                    ->getRepository('IcsePublicBundle:SiteText')
                    ->findOneByName($name);

      return $textObject ? $textObject->getText() : "";
    }

  public function joinAction(Request $request)
    {
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
                        true => 'Yes!',
                        false => 'No, just notify me about any concerts.'
                      ),
                      'expanded' => true,
                      'multiple' => false,
                      'label' => 'Are you interested in joining ICSE?'
                    ))
              ->add('instrument', 'choice', array(
                      'choices' => array(
                        'Violin' => 'Violin',
                        'Viola' => 'Viola',
                        'Cello' => '\'Cello',
                        'Double Bass' => 'Double Bass',
                        'other' => 'Other'
                      ),
                      'expanded' => true,
                      'multiple' => false,
                      'required' => false
                    ))
              ->add('other_instrument', 'text', array('label' => ' ', 'required' => false))
              ->add('standard', 'text', array('label' => 'Give us an idea of your standard', 'required' => false))
              ->getForm(); 

      if ($request->getMethod() == 'POST')
        {
          $form->bindRequest($request);
          /* Do more error checking, and fill in implied data */
          if ($subscriber->getLogin())
            {
              $info = ldap_get_info($subscriber->getLogin());
              if (!$info)
                $form->addError(new \Symfony\Component\Form\FormError("Not a valid Imperial login username."));
              else 
                {
                  if (!$subscriber->getDepartment())
                    $subscriber->setDepartment($info[2]);
                }
            }
          else
            {
              $subscriber->setDepartment(null);
            }
          if ($subscriber->isPlayer())
            {
              if (!$subscriber->getInstrument() || ($subscriber->getInstrument() == 'other' && !$subscriber->getOtherInstrument()))
                $form->addError(new \Symfony\Component\Form\FormError("Please specify the instrument you play."));
              if ($subscriber->getInstrument() == 'other')
                $subscriber->setInstrument($subscriber->getOtherInstrument());
              if (!$subscriber->getStandard())
                $form->addError(new \Symfony\Component\Form\FormError("Please indicate your playing standard."));
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
              $em = $this->getDoctrine()->getEntityManager();
              $em->persist($subscriber);
              $em->flush();
              return $this->redirect($this->generateUrl('IcsePublicBundle_join_success', array('name' => $subscriber->getFirstName())));
            }
          else
            {
              if ($subscriber->getLogin())
                $username_or_email = $subscriber->getLogin();
              else
                $username_or_email = $subscriber->getEmail();
            }
        }
      return $this->render('IcsePublicBundle:Default:join.html.twig', array('join_intro' => $this->getSiteText('join_intro'),
                                                                            'form' => $form->createView(),
                                                                            'username_or_email' => $username_or_email));
    }


  public function join_successAction($name)
    {
      return $this->render('IcsePublicBundle:Default:generic_page.html.twig', array('pageId' => 'join',
                                                                                    'pageTitle' => 'Join Us',
                                                                                    'pageBody' => "Thanks " . $name . ", we'll get back to you shortly."));
    }

  public function query_usernameAction(Request $request)
    {
      $input = $request->query->get('input');
      $output = array();
      $emailValidator = new \Symfony\Component\Validator\Constraints\EmailValidator;
      $emailConstraint = new \Symfony\Component\Validator\Constraints\Email;
      $emailConstraint->checkMX = true;
      if ($input != "" && $emailValidator->isValid($input, $emailConstraint))
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
                  $email = ldap_get_mail($input);
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
