<?php
namespace Icse\MembersBundle\Service;

use Symfony\Component\HttpFoundation\Response; 
use Doctrine\ORM\EntityManager;

class CalendarEvents
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
 
    public function iter($types = ['rehearsal', 'event'])
    {
        $iterator = new \AppendIterator(); 

        if (in_array('rehearsal', $types))
        {
            $rehearsals = $this->em->getRepository('IcseMembersBundle:Rehearsal')->findAll();
            $iterator->append(new \ArrayIterator($rehearsals));
        }

        if (in_array('event', $types))
        {
            $concerts = $this->em->getRepository('IcsePublicBundle:Event')->findEventsWithKnownTime();
            $iterator->append(new \ArrayIterator($concerts));
        }

        return $iterator;
    }

    public function type($e)
    {
        return strtolower(end(explode('\\', get_class($e))));
    }

}