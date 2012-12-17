<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Icse\PublicBundle\Entity\Event;

class EventsController extends Controller
{
    public function eventAction($id, $slug)
    {
        $event = $this->getDoctrine()
                ->getRepository('IcsePublicBundle:Event')
                ->findOneById($id);

        if ($slug !== $event->getSlug()) {
            return $this->redirect($this->generateUrl('IcsePublicBundle_event', $event), 301); 
        }


        return new Response("hello");
    }
}
