<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Icse\PublicBundle\Entity\Subscriber;


if (!function_exists('ldap_get_mail'))
  {
    function ldap_get_mail($login)
    {
      if ($login == "js10")
        {
          return "john.smith10@imperial.ac.uk";
        }
      else
        {
          return null;
        }
    }

    function ldap_get_names($login)
    {
      return array("John", "Smith");
    }

    function ldap_get_info($login)
    {
      return array(2 => "Department of X");
    }
  }

class DefaultController extends Controller
{
  private function getSiteText($name)
    {
      $textObject = $this->getDoctrine()
                    ->getRepository('IcsePublicBundle:SiteText')
                    ->findOneByName($name);

      return $textObject ? $textObject->getText() : "";
    }

  public function indexAction()
    {
      return $this->render('IcsePublicBundle:Default:home.html.twig', array('home_intro' => $this->getSiteText('home_intro')));
    }

  public function aboutAction()
    {
      return $this->render('IcsePublicBundle:Default:generic_page.html.twig', array('pageId' => 'about',
                                                                                    'pageTitle' => 'About Us',
                                                                                    'pageBody' => $this->getSiteText('about')));
    }

  public function joinAction(Request $request)
    {
      $subscriber = new Subscriber();

      $form = $this->createFormBuilder()
              ->add('first_name', 'text')
              ->add('last_name', 'text')
              ->add('email', 'email')
              ->add('login', 'text', array('required' => false))
              ->add('department', 'text', array('required' => false))
              ->add('player', 'choice', array(
                      'choices' => array(
                        'true' => 'Yes!',
                        'false' => 'No, just notify me about any concerts.'
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
          $data = $form->getData();
          return $this->render('IcsePublicBundle:Default:join.html.twig', array('join_intro' => $this->getSiteText('join_intro')));
        }
      else
        {
          return $this->render('IcsePublicBundle:Default:join.html.twig', array('join_intro' => $this->getSiteText('join_intro'),
                                                                                'form' => $form->createView()));
        }
    }

  public function query_usernameAction(Request $request)
    {
      $input = $request->query->get('input');
      $output = array();
      if (filter_var($input, FILTER_VALIDATE_EMAIL))
        {
          $output['type'] = 'email';
        }
      else
        {
          $email_from_username = ldap_get_mail($input);
          if ($email_from_username)
            {
              $output['type'] = 'username';
              $output['email'] = $email_from_username;
              $names = ldap_get_names($input);
              $output['first_name'] = $names[0];
              $output['last_name'] = $names[count($names)-1];
              $info = ldap_get_info($input);
              $output['department'] = $info[2];
            }
          else
            {
              $output['type'] = 'invalid';
            }
        }
      return new Response(json_encode($output));
    }

  public function supportAction()
    {
      return $this->render('IcsePublicBundle:Default:generic_page.html.twig', array('pageId' => 'support',
                                                                                    'pageTitle' => 'Support Us',
                                                                                    'pageBody' => $this->getSiteText('support')));
    }

  public function contactAction()
    {
      return $this->render('IcsePublicBundle:Default:generic_page.html.twig', array('pageId' => 'contact',
                                                                                    'pageTitle' => 'Contact Us',
                                                                                    'pageBody' => $this->getSiteText('contact')));
    }
}
