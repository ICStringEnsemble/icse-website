<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
  public function indexAction()
    {
      return $this->render('IcsePublicBundle:Default:home.html.twig', array());
    }

  public function aboutAction()
    {
      return $this->render('IcsePublicBundle:Default:about.html.twig', array());
    }

  public function supportAction()
    {
      return $this->render('IcsePublicBundle:Default:support.html.twig', array());
    }

  public function contactAction()
    {
      return $this->render('IcsePublicBundle:Default:contact.html.twig', array());
    }
}
