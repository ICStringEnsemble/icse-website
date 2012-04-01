<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Icse\PublicBundle\Entity\Subscriber;

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
