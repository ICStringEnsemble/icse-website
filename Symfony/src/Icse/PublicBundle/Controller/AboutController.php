<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AboutController extends Controller
{
    private function getSiteSection($name)
    {
        $section = $this->getDoctrine()
                    ->getRepository('IcsePublicBundle:SiteSection')
                    ->findOneByName($name);
        return ['text' => $section->getText(), 'image' => $section->getPicture()];
    }

    public function ensembleAction()
    {
        $section = $this->getSiteSection('about_ensemble');
        return $this->render('IcsePublicBundle:About:generic_page.html.twig', [
            'section' => $section,
            'pageTitle' => 'About the Ensemble',
            'currentSubSection' => 'ensemble'
        ]);
    }

    public function conductorAction()
    {
        $section = $this->getSiteSection('about_conductor');
        return $this->render('IcsePublicBundle:About:generic_page.html.twig', [
            'section' => $section,
            'pageTitle' => 'The Conductor',
            'currentSubSection' => 'conductor'
        ]);
    }

    public function committeeAction()
    {
        $committee_members = $this->getDoctrine()->getRepository('IcseMembersBundle:CommitteeRole')->findCurrent();
        return $this->render('IcsePublicBundle:About:committee.html.twig', [
            'committee_members' => $committee_members
        ]);
    }
}
