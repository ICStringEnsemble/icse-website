<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Common\Tools; 

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
     * @var text $description
     */
    private $description;

    /**
     * @var datetime $starts_at
     */
    private $starts_at;

    /**
     * @var string $location
     */
    private $location;

    /**
     * @var datetime $updated_at
     */
    private $updated_at;

    /**
     * @var integer $updated_by
     */
    private $updated_by;


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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set starts_at
     *
     * @param datetime $startsAt
     */
    public function setStartsAt($startsAt)
    {
        $this->starts_at = $startsAt;
    }

    /**
     * Get starts_at
     *
     * @return datetime 
     */
    public function getStartsAt()
    {
        return $this->starts_at;
    }

    /**
     * @return \DateTime 
     */
    public function getEndsAt()
    {
        $ends_at = clone $this->starts_at;
        $ends_at->modify('+3 hours');
        return $ends_at;
    }

    /**
     * Set location
     *
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set updated_by
     *
     * @param integer $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updated_by = $updatedBy;
    }

    /**
     * Get updated_by
     *
     * @return integer 
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $images;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add images
     *
     * @param Icse\PublicBundle\Entity\Image $images
     * @return Event
     */
    public function addImage(\Icse\PublicBundle\Entity\Image $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param Icse\PublicBundle\Entity\Image $images
     */
    public function removeImage(\Icse\PublicBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }
    /**
     * @var Icse\PublicBundle\Entity\Image
     */
    private $poster;


    /**
     * Set poster
     *
     * @param Icse\PublicBundle\Entity\Image $poster
     * @return Event
     */
    public function setPoster(\Icse\PublicBundle\Entity\Image $poster = null)
    {
        $this->poster = $poster;
    
        return $this;
    }

    /**
     * Get poster
     *
     * @return Icse\PublicBundle\Entity\Image 
     */
    public function getPoster()
    {
        return $this->poster;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $performances;


    /**
     * Add performances
     *
     * @param Icse\PublicBundle\Entity\PerformanceOfAPiece $performances
     * @return Event
     */
    public function addPerformance(\Icse\PublicBundle\Entity\PerformanceOfAPiece $performances)
    {
        $this->performances[] = $performances;
    
        return $this;
    }

    /**
     * Remove performances
     *
     * @param Icse\PublicBundle\Entity\PerformanceOfAPiece $performances
     */
    public function removePerformance(\Icse\PublicBundle\Entity\PerformanceOfAPiece $performances)
    {
        $this->performances->removeElement($performances);
    }

    /**
     * Get performances
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPerformances()
    {
        return $this->performances;
    }

    public function getSlug()
    {
        return Tools::slugify($this->getName());
    }
}