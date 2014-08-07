<?php

namespace Icse\MembersBundle\Tests\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Icse\MembersBundle\Entity\CommitteeRole;
use Icse\MembersBundle\Entity\Member;

class MemberTest extends \PHPUnit_Framework_TestCase {

    public function testGetRoles()
    {
        $committee_role = new CommitteeRole();
        $committee_role->setStartYear(2010);

        $member = new Member();
        $member->addCommitteeRole($committee_role);

        $this->assertEquals(array('ROLE_USER'), $member->getRoles(new \DateTime('2010-05-01')), "Not admin in May 2010");
        $this->assertEquals(array('ROLE_ADMIN'), $member->getRoles(new \DateTime('2010-06-01')), "Admin in June 2010");
        $this->assertEquals(array('ROLE_ADMIN'), $member->getRoles(new \DateTime('2011-09-01')), "Admin in September 2011");
        $this->assertEquals(array('ROLE_USER'), $member->getRoles(new \DateTime('2011-10-01')), "Not admin in October 2011");

        $member2 = new Member();
        $this->assertEquals(array('ROLE_USER'), $member2->getRoles(new \DateTime('2011-09-01')), "Never an admin");
    }

    public function testHasCurrentMembership()
    {
        $member = new Member;
        $member->setLastPaidMembershipOn(new \DateTime("31st July 2010"));

        $this->assertEquals(true, $member->hasCurrentMembership(new \DateTime("15th January 2010")));
        $this->assertEquals(true, $member->hasCurrentMembership(new \DateTime("31st July 2010")));
        $this->assertEquals(false, $member->hasCurrentMembership(new \DateTime("1st August 2010")));
        $this->assertEquals(false, $member->hasCurrentMembership(new \DateTime("1st January 2011")));

        $member->setLastPaidMembershipOn(new \DateTime("1st August 2010"));
        $this->assertEquals(true, $member->hasCurrentMembership(new \DateTime("31st July 2010")));
        $this->assertEquals(true, $member->hasCurrentMembership(new \DateTime("1st August 2010")));
        $this->assertEquals(true, $member->hasCurrentMembership(new \DateTime("31st December 2010")));
        $this->assertEquals(true, $member->hasCurrentMembership(new \DateTime("1st January 2011")));
        $this->assertEquals(true, $member->hasCurrentMembership(new \DateTime("31st July 2011")));
        $this->assertEquals(false, $member->hasCurrentMembership(new \DateTime("1st August 2011")));
        $this->assertEquals(false, $member->hasCurrentMembership(new \DateTime("31st December 2011")));
        $this->assertEquals(false, $member->hasCurrentMembership(new \DateTime("1st January 2012")));
    }

}
 