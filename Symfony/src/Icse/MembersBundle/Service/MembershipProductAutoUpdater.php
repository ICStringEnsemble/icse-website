<?php
namespace Icse\MembersBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ValidatorInterface;
use Common\Tools;
use Icse\MembersBundle\Entity\Member;

class MembershipProductAutoUpdater
{
    private $eactivities;
    private $em;

    private $last_product;

    public function __construct(EActivities $eactivities, EntityManager $em)
    {
        $this->eactivities = $eactivities;
        $this->em = $em;
    }

    public function start()
    {
        $product = $this->eactivities->get_membership_product();
        $this->em->merge($product);
        $this->em->flush();
        $this->last_product = $product;
    }

    public function stats_string()
    {
        $p = $this->last_product;
        return $p->getStartsAt()->format("Y")."-".$p->getEndsAt()->format("Y") . ", £".$p->getPrice();
    }
} 
