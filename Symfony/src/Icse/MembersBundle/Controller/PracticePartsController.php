<?php

namespace Icse\MembersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 


class PracticePartsController extends Controller
{
    public function legacyAction()
    {
      return $this->render('IcseMembersBundle:PracticeParts:legacy.html.twig', array());
    }
}
