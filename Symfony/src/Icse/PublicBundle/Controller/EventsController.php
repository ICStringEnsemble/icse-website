<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Icse\PublicBundle\Entity\Event;

class EventsController extends Controller
{
    public function pastEventsIndexAction()
    {
        $dm = $this->getDoctrine(); 
        $first_year = $dm->getRepository('IcsePublicBundle:Event')
                         ->yearOfFirstEvent();
        $last_year = $dm->getRepository('IcsePublicBundle:Event')
                         ->yearOfMostRecentEvent();
        return $this->render('IcsePublicBundle:Events:past_events.html.twig', array('first_year' => $first_year,
                                                                                    'last_year' => $last_year));
    }

    public function futureEventsIndexAction()
    {
        $dm = $this->getDoctrine(); 
        $events = $dm->getRepository('IcsePublicBundle:Event')
                        ->findFutureEvents(); 
        return $this->render('IcsePublicBundle:Events:future_events.html.twig', array('events' => $events));
    }


    public function pastEventsInYearAction($year)
    {
        $dm = $this->getDoctrine(); 
        $events = $dm->getRepository('IcsePublicBundle:Event')
                        ->findPastEventsInYear($year); 
        return $this->render('IcsePublicBundle:Events:past_events_in_year.html.twig', array('year' => $year, 'events' => $events));
    }

    public function eventAction($id, $slug)
    {
        /** @var $event Event */
        $event = $this->getDoctrine()
                ->getRepository('IcsePublicBundle:Event')
                ->findOneById($id);

        if ($slug !== $event->getSlug()) {
            return $this->redirect($this->generateUrl('IcsePublicBundle_event', $event), 301); 
        }


        return $this->render('IcsePublicBundle:Events:event.html.twig', array('event' => $event));
    }
}
