<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Icse\PublicBundle\Entity\Subscriber;
use Icse\PublicBundle\Entity\Event;

class DefaultController extends Controller
{
  private function getSiteText($name)
    {
      $textObject = $this->getDoctrine()
                    ->getRepository('IcsePublicBundle:SiteText')
                    ->findOneByName($name);

      return $textObject ? $textObject->getText() : "";
    }

  public function indexAction()
    {
      $slideshow_images = $this->getDoctrine()
                               ->getRepository('IcsePublicBundle:Image')
                               ->findByCategory('Homepage Slideshow');

      $today_events = $this->getDoctrine()
                            ->getRepository('IcsePublicBundle:Event')
                            ->findTodayEvents(); 

      $tomorrow_events = $this->getDoctrine()
                            ->getRepository('IcsePublicBundle:Event')
                            ->findTomorrowEvents(); 

      $thisweek_events = $this->getDoctrine()
                            ->getRepository('IcsePublicBundle:Event')
                            ->findLaterThisWeekEvents(); 

      $nextweek_events = $this->getDoctrine()
                            ->getRepository('IcsePublicBundle:Event')
                            ->findNextWeekEvents(); 

      $future_events = $this->getDoctrine()
                            ->getRepository('IcsePublicBundle:Event')
                            ->findFutureEvents();

      $past_events = $this->getDoctrine()
                          ->getRepository('IcsePublicBundle:Event')
                          ->findPastEvents();

      return $this->render('IcsePublicBundle:Default:home.html.twig', array('home_intro' => $this->getSiteText('home_intro'),
                                                                            'today_events' => $today_events,
                                                                            'tomorrow_events' => $tomorrow_events,
                                                                            'thisweek_events' => $thisweek_events,
                                                                            'nextweek_events' => $nextweek_events,
                                                                            'future_events' => $future_events,
                                                                            'past_events' => $past_events,
                                                                            'slideshow_images' => $slideshow_images));
    }

  public function aboutAction()
    {
      return $this->render('IcsePublicBundle:Default:generic_page.html.twig', array('pageId' => 'about',
                                                                                    'pageTitle' => 'About Us',
                                                                                    'pageBody' => $this->getSiteText('about')));
    }

  public function supportAction()
    {
      return $this->render('IcsePublicBundle:Default:generic_page.html.twig', array('pageId' => 'support',
                                                                                    'pageTitle' => 'Support Us',
                                                                                    'pageBody' => $this->getSiteText('support')));
    }

  public function contactAction()
    {
      return $this->render('IcsePublicBundle:Default:generic_page.html.twig', array('pageId' => 'contact',
                                                                                    'pageTitle' => 'Contact Us',
                                                                                    'pageBody' => $this->getSiteText('contact')));
    }

  public function signup_resultsAction(Request $request)
    {
      $show = $request->query->get('show');
      if (!isset($show))
        {
          $show = "all";
        }
      $repository =  $this->getDoctrine()
                          ->getRepository('IcsePublicBundle:Subscriber');

      if ($show == "player")
        {
          $signups = $repository->findAllPlayers();
        }
      else if ($show == "concertgoer")
        {
          $signups = $repository->findAllConcertGoers();
        }
      else if ($show == "violin")
        {
          $signups = $repository->findByInstrumentMostRecentFirst("Violin");
        }
      else if ($show == "viola")
        {
          $signups = $repository->findByInstrumentMostRecentFirst("Viola");
        }
      else if ($show == "cello")
        {
          $signups = $repository->findByInstrumentMostRecentFirst("Cello");
        }
      else if ($show == "doublebass")
        {
          $signups = $repository->findByInstrumentMostRecentFirst("Double Bass");
        }
      else if ($show == "otherplayer")
        {
          $signups = $repository->findAllOtherInstrument(); 
        }
      else if ($show == "all")
        {
          $signups = $repository->findAllMostRecentFirst();
        }
      else
        {
          throw new notFoundHttpException("Cannot limit to " . $show);
        }

      $people_count = count($signups);
      return $this->render('IcsePublicBundle:Default:signup_results.html.twig', array('signups' => $signups,
                                                                                      'currentShow' => $show,
                                                                                      'people_count' => $people_count));
    }
}
