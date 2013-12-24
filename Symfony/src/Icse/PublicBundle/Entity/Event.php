<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Common\Tools;
use Icse\MembersBundle\Entity\Member;

/**
 * Icse\PublicBundle\Entity\Event
 */
class Event
{
    /**
     * @var integer $id
     */
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $description
     */
    private $description;
    /**
     * @var Venue
     */
    private $location;
    /**
     * @var \DateTime $starts_at
     */
    private $starts_at;
    /**
     * @var \DateTime
     */
    private $updated_at;
    /**
     * @var Member
     */
    private $updated_by;
    /**
     * @var Image
     */
    private $poster;
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $performances;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->performances = new ArrayCollection();
    }

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
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     */
    public function setStartsAt($startsAt)
    {
        $this->starts_at = $startsAt;
    }

    /**
     * @return \DateTime 
     */
    public function getEndsAt()
    {
        if ($this->starts_at == null)
        {
            return null;
        }
        else
        {
            $ends_at = clone $this->starts_at;
            $ends_at->modify('+3 hours');
            return $ends_at;
        }
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
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    public function getSlug()
    {
        return Tools::slugify($this->getName());
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
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get poster
     *
     * @return Image
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * Set poster
     *
     * @param Image $poster
     * @return Event
     */
    public function setPoster(Image $poster = null)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Add performances
     *
     * @param PerformanceOfAPiece $performances
     * @return Event
     */
    public function addPerformance(PerformanceOfAPiece $performances)
    {
        $this->performances[] = $performances;
    
        return $this;
    }

    /**
     * Remove performances
     *
     * @param PerformanceOfAPiece $performances
     */
    public function removePerformance(PerformanceOfAPiece $performances)
    {
        $this->performances->removeElement($performances);
    }

    /**
     * Get performances
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPerformances()
    {
        return $this->performances;
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
     * @return Event
     */
    public function setUpdatedBy(Member $updatedBy = null)
    {
        $this->updated_by = $updatedBy;

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
     * @return Event
     */
    public function setLocation(Venue $location = null)
    {
        $this->location = $location;

        return $this;
    }
}