<?php

namespace Icse\MembersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommitteeRole
 */
class CommitteeRole
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $position_name;

    /**
     * @var integer
     */
    private $start_year;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set positionName
     *
     * @param string $positionName
     * @return CommitteeRole
     */
    public function setPositionName($positionName)
    {
        $this->position_name = $positionName;

        return $this;
    }

    /**
     * Get positionName
     *
     * @return string 
     */
    public function getPositionName()
    {
        return $this->position_name;
    }

    /**
     * Set startYear
     *
     * @param integer $startYear
     * @return CommitteeRole
     */
    public function setStartYear($startYear)
    {
        $this->start_year = $startYear;

        return $this;
    }

    /**
     * Get startYear
     *
     * @return integer 
     */
    public function getStartYear()
    {
        return $this->start_year;
    }
    public function getstart_year()
    {
        return $this->getStartYear();
    }

    /**
     * @var \Icse\MembersBundle\Entity\Member
     */
    private $member;


    /**
     * Set member
     *
     * @param \Icse\MembersBundle\Entity\Member $member
     * @return CommitteeRole
     */
    public function setMember(\Icse\MembersBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \Icse\MembersBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }
    /**
     * @var integer
     */
    private $sort_position;


    /**
     * Set sort_position
     *
     * @param integer $sortPosition
     * @return CommitteeRole
     */
    public function setSortPosition($sortPosition)
    {
        $this->sort_position = $sortPosition;

        return $this;
    }

    /**
     * Get sort_position
     *
     * @return integer 
     */
    public function getSortPosition()
    {
        return $this->sort_position;
    }
}
