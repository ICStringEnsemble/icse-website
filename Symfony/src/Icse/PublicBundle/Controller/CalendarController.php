<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sabre\VObject;

use Icse\PublicBundle\Entity\Event;

class CalendarController extends Controller
{
    public function testAction()
    {
        $vcalendar = new VObject\Component\VCalendar();

        $vcalendar->add('X-WR-CALNAME', 'ICSE');
        $vcalendar->add('X-WR-CALDESC', 'ICSE Events Calendar, oh this changes too');
        $vcalendar->add('X-PUBLISHED-TTL', 'PT15M');

        $vcalendar->add('VEVENT', [
            'SUMMARY' => 'Birthday party again',
            'DTSTART' => new \DateTime('2013-09-17 3pm'),
            'DTEND' => new \DateTime('2013-09-17 4pm'),
            'DTSTAMP' => new \DateTime('now', new \DateTimeZone('utc')),
            'LAST-MODIFIED' => new \DateTime('now', new \DateTimeZone('utc')),
            'UID' => '10@union.ic.ac.uk/arts/stringensemble',
            'DESCRIPTION' => 'Created at '. (new \DateTime())->format('Y-m-d H:i:s'),
        ]);

        $vcalendar->add('VEVENT', [
            'SUMMARY' => 'Another Thing',
            'DTSTART' => new \DateTime('2013-09-18 10am'),
            'DTEND' => new \DateTime('2013-09-18 1pm'),
            'DTSTAMP' => new \DateTime('now', new \DateTimeZone('utc')),
            'LAST-MODIFIED' => new \DateTime('now', new \DateTimeZone('utc')),
            'UID' => '11@union.ic.ac.uk/arts/stringensemble',
            'DESCRIPTION' => 'Created at '. (new \DateTime())->format('Y-m-d H:i:s'),
        ]);


        $response = new Response($vcalendar->serialize(),
                            200,
                            [
                                'Cache-Control' => 'private',
                                'Connection' => 'close',
                                // 'Content-Type' => 'text/calendar; charset=utf-8',
                            ]);

        $response->setExpires(new \DateTime('+15 minutes'));

        return $response;
    }

    public function membersAction()
    {
        $dm = $this->getDoctrine();
        $rehearsals = $dm->getRepository('IcseMembersBundle:Rehearsal')
                         ->findAll();

        $vcalendar = new VObject\Component\VCalendar();

        $vcalendar->add('X-WR-CALNAME', 'ICSE Members');
        $vcalendar->add('X-WR-CALDESC', 'Rehearsals and events calendar for Imperial College String Ensemble');
        $vcalendar->add('X-PUBLISHED-TTL', 'PT15M');

        foreach ($rehearsals as $r)
        {
            $title = "Rehearsal";
            if ($r->getName())
            {
                $title .= ': '. $r->getName();
            }

            $vcalendar->add('VEVENT', [
                'SUMMARY' => $title,
                'DTSTART' => $r->getStartsAt(),
                'DTEND' =>  $r->getEndsAt(),
                'DTSTAMP' => new \DateTime('now', new \DateTimeZone('utc')),
                'LAST-MODIFIED' => $r->getUpdatedAt()->setTimezone(new \DateTimeZone('utc')),
                'UID' =>  'R'.$r->getId().':'.$r->getStartsAt()->format('Ymd\THis').'@union.ic.ac.uk/arts/stringensemble',
                'LOCATION' => $r->getLocation()->getName(),
                'DESCRIPTION' => $r->getComments() . "\n" . 'Loaded at '. (new \DateTime())->format('Y-m-d H:i:s'),
            ]);            
        }

        $response = new Response($vcalendar->serialize(),
                            200,
                            [
                                'Cache-Control' => 'private',
                                'Connection' => 'close',
                                // 'Content-Type' => 'text/calendar; charset=utf-8',
                            ]);
        $response->setExpires(new \DateTime('+15 minutes'));
        return $response;
    }

}
