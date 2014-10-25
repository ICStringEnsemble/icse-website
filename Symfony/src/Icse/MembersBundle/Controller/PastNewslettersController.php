<?php

namespace Icse\MembersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 


class PastNewslettersController extends Controller
{
    public function indexAction()
    {
        $newsletters = $this->getDoctrine()->getRepository('IcseMembersBundle:SentNewsletter')
            ->findLastNToMailinglist(5);

        return $this->render('IcseMembersBundle:Default:past_newsletters.html.twig', ['newsletters' => $newsletters]);
    }
}
