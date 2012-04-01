<?php

namespace Icse\MembersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
      {
        return $this->render('IcsePublicBundle:Default:generic_page.html.twig', array('pageId' => 'members',
                                                                                      'pageTitle' => 'Members',
                                                                                      'pageBody' => 'This is a secure area.'));
      }
}
