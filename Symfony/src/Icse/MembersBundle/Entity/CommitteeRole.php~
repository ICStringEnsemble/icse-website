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
     * @var Member
     */
    private $member;


    /**
     * Set member
     *
     * @param Member $member
     * @return CommitteeRole
     */
    public function setMember(Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return Member
     */
    public function getMember()
    {
        return $this->member;
    }
    /**
     * @var integer
     */
    private $sort_index;


    /**
     * Set sort_index
     *
     * @param integer $sort_index
     * @return CommitteeRole
     */
    public function setSortIndex($sort_index)
    {
        $this->sort_index = $sort_index;

        return $this;
    }

    /**
     * Get sort_index
     *
     * @return integer 
     */
    public function getSortIndex()
    {
        return $this->sort_index;
    }
}
