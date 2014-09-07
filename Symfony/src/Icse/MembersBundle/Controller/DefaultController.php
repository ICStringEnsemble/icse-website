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
        $events = ['rehearsal' => [], 'event' => []];
        
        $event_lib = $this->get('icse.calendar_events');

        foreach ($event_lib->iter() as $e)
        {
            $type = $event_lib->type($e);
            
            $title = '';
            $all_day = false;
            if ($type == 'rehearsal')
            {
                $title = 'Rehearsal';
            }
            elseif ($type == 'event')
            {
                $title = $e->getName();
                if (!$e->isStartTimeKnown()) $all_day = true;
            }

            if ($e->getLocation())
            {
                $title .= ' ('.$e->getLocation()->getName().')';
            }

            array_push($events[$type], [
                'title' => $title,
                'start' => $e->getStartsAt() ? $e->getStartsAt()->format('M d Y H:i:s') : '',
                'end' => $e->getEndsAt() ? $e->getEndsAt()->format('M d Y H:i:s') : '',
                'allDay' => $all_day,
            ]);
        }

        return $this->render('IcseMembersBundle:Default:calendar.html.twig', [
            'rehearsals' => $events['rehearsal'],
            'events' => $events['event'],
        ]);
    }
}
