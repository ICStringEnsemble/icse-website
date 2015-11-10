<?php

namespace Icse\MembersBundle\Controller;

use Icse\PublicBundle\Entity\Event;
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

        /** @var Event $e */
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

            $start = $e->getStartsAt();
            $end = $e->getApproxEndsAt();

            array_push($events[$type], [
                'title' => $title,
                'start' => $start ? $start->format('M d Y H:i:s') : '',
                'end' => $end ? $end->format('M d Y H:i:s') : '',
                'allDay' => $all_day,
            ]);
        }

        return $this->render('IcseMembersBundle:Default:calendar.html.twig', [
            'rehearsals' => $events['rehearsal'],
            'events' => $events['event'],
        ]);
    }

    public function membershipPaymentInfoFragmentAction($username = null)
    {
        if ($username !== null)
        {
            $user = $this->getDoctrine()
                ->getRepository('IcseMembersBundle:Member')
                ->findOneByUsername($username);
        }
        else
        {
            $user = $this->get('security.token_storage')->getToken()->getUser();
        }

        $membership_product = $this->getDoctrine()
            ->getRepository('IcseMembersBundle:MembershipProduct')
            ->findCurrent();

        return $this->render('IcseMembersBundle:Default:membership_payment_info_fragment.html.twig', [
            'user' => $user,
            'product' => $membership_product,
        ]);
    }
}
