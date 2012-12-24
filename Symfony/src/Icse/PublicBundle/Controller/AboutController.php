<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AboutController extends Controller
{
  private function getSiteText($name)
    {
      $textObject = $this->getDoctrine()
                    ->getRepository('IcsePublicBundle:SiteSection')
                    ->findOneByName($name);

      return $textObject ? $textObject->getText() : "";
    }

    public function ensembleAction()
    {
        return $this->render('IcsePublicBundle:About:ensemble.html.twig', array('pageBody' => $this->getSiteText('about_ensemble')));
    }

    public function conductorAction()
    {
        return $this->render('IcsePublicBundle:About:conductor.html.twig', array('pageBody' => $this->getSiteText('about_conductor')));
    }
}
