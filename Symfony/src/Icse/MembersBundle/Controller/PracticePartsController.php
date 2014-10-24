<?php

namespace Icse\MembersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 


class PracticePartsController extends Controller
{
    public function indexAction()
    {
        $events = $this->getDoctrine()->getRepository('IcsePublicBundle:Event')->findUpcomingEventsWithPracticeParts();
        return $this->render('IcseMembersBundle:PracticeParts:index.html.twig', ['events' => $events]);
    }

    public function legacyAction()
    {
      return $this->render('IcseMembersBundle:PracticeParts:legacy.html.twig', []);
    }
}
