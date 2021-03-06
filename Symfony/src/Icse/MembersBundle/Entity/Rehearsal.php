<?php

namespace Icse\MembersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Icse\PublicBundle\Entity\Venue;

/**
 * Icse\MembersBundle\Entity\Rehearsal
 */
class Rehearsal
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $comments;
    /**
     * @var \DateTime
     */
    private $starts_at;
    /**
     * @var \DateTime
     */
    private $updated_at;
    /**
     * @var Venue
     */
    private $location;
    /**
     * @var Member
     */
    private $updated_by;
    /**
     * @var \DateTime
     */
    private $ends_at;

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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Rehearsal
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return Rehearsal
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get starts_at
     *
     * @return \DateTime
     */
    public function getStartsAt()
    {
        return $this->starts_at;
    }

    /**
     * Set starts_at
     *
     * @param \DateTime $startsAt
     * @return Rehearsal
     */
    public function setStartsAt($startsAt)
    {
        $this->starts_at = $startsAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Rehearsal
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get location
     *
     * @return Venue
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set location
     *
     * @param Venue $location
     * @return Rehearsal
     */
    public function setLocation(Venue $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get updated_by
     *
     * @return Member
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

    /**
     * Set updated_by
     *
     * @param Member $updatedBy
     * @return Rehearsal
     */
    public function setUpdatedBy(Member $updatedBy = null)
    {
        $this->updated_by = $updatedBy;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndsAt()
    {
        return $this->ends_at;
    }

    public function getApproxEndsAt()
    {
        if ($this->ends_at !== null)
        {
            return $this->ends_at;
        }
        else
        {
            $ends_at = clone $this->starts_at;
            $ends_at->modify('+3 hours');
            return $ends_at;
        }
    }

    /**
     * Set ends_at
     *
     * @param \DateTime $endsAt
     * @return Rehearsal
     */
    public function setEndsAt($endsAt)
    {
        $this->ends_at = $endsAt;

        return $this;
    }
}
