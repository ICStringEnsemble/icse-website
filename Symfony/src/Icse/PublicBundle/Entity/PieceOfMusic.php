<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Icse\PublicBundle\Entity\PieceOfMusic
 */
class PieceOfMusic
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
     * @return PieceOfMusic
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
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
     * @var \DateTime $updated_at
     */
    private $updated_at;

    /**
     * @var integer $updated_by
     */
    private $updated_by;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $performances;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->performances = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return PieceOfMusic
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    
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
     * Set updated_by
     *
     * @param integer $updatedBy
     * @return PieceOfMusic
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updated_by = $updatedBy;
    
        return $this;
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
     * Add performances
     *
     * @param Icse\PublicBundle\Entity\PerformanceOfAPiece $performances
     * @return PieceOfMusic
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
    /**
     * @var string $composer
     */
    private $composer;


    /**
     * Set composer
     *
     * @param string $composer
     * @return PieceOfMusic
     */
    public function setComposer($composer)
    {
        $this->composer = $composer;
    
        return $this;
    }

    /**
     * Get composer
     *
     * @return string 
     */
    public function getComposer()
    {
        return $this->composer;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $practice_parts;


    /**
     * Add practice_parts
     *
     * @param Icse\MembersBundle\Entity\PracticePart $practiceParts
     * @return PieceOfMusic
     */
    public function addPracticePart(\Icse\MembersBundle\Entity\PracticePart $practiceParts)
    {
        $this->practice_parts[] = $practiceParts;
    
        return $this;
    }

    /**
     * Remove practice_parts
     *
     * @param Icse\MembersBundle\Entity\PracticePart $practiceParts
     */
    public function removePracticePart(\Icse\MembersBundle\Entity\PracticePart $practiceParts)
    {
        $this->practice_parts->removeElement($practiceParts);
    }

    /**
     * Get practice_parts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPracticeParts()
    {
        return $this->practice_parts;
    }
}