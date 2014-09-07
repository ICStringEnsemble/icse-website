<?php
namespace Icse\MembersBundle\Service;

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
            $concerts = $this->em->getRepository('IcsePublicBundle:Event')->findEventsWithKnownDate();
            $iterator->append(new \ArrayIterator($concerts));
        }

        return $iterator;
    }

    public function type($e)
    {
        $class_path = explode('\\', get_class($e));
        return strtolower(end($class_path));
    }

}