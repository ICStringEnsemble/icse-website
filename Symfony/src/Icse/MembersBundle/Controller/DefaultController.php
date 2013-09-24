<?php

namespace Icse\MembersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 


class DefaultController extends Controller
{
    public function returnToLastPageAction(Request $request)
    {
        $cookies = $request->cookies;
        $redirect_route = null;
        if ($cookies->has('last_members_page')) {
            $redirect_route = $cookies->get('last_members_page');
        }
        if (!$redirect_route || $redirect_route == "IcseMembersBundle_return") {
            $redirect_route = 'IcseMembersBundle_home';
        }
        return $this->redirect($this->generateUrl($redirect_route)); 
    }

    public function indexAction()
    {
        return $this->render('IcseMembersBundle:Default:index.html.twig', array());
    }

    public function calendarAction()
    {
        $dm = $this->getDoctrine();
        $rehearsals = $dm->getRepository('IcseMembersBundle:Rehearsal')
                         ->findAll();

        $events = [];

        foreach ($rehearsals as $r)
        {
            $start_time = $r->getStartsAt();

            $title = 'Rehearsal';

            if ($r->getLocation())
            {
                $title .= ' ('.$r->getLocation()->getName().')';
            }

            $event = [
                'title' => $title,
                'start' => $r->getStartsAt() ? $r->getStartsAt()->format('M d Y H:i:s') : '',
                'end' => $r->getEndsAt() ? $r->getEndsAt()->format('M d Y H:i:s') : '',
                'allDay' => false,
            ];

            array_push($events, $event);
        }


        return $this->render('IcseMembersBundle:Default:calendar.html.twig', ['events' => $events]);
    }
}
