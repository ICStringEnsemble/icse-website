<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * PieceOfMusic
 */
class PieceOfMusic
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $composer;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $updated_at;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups({"performances"})
     */
    private $performances;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $practice_parts;

    /**
     * @var \Icse\MembersBundle\Entity\Member
     */
    private $updated_by;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->performances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->practice_parts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add performances
     *
     * @param \Icse\PublicBundle\Entity\PerformanceOfAPiece $performances
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
     * @param \Icse\PublicBundle\Entity\PerformanceOfAPiece $performances
     */
    public function removePerformance(\Icse\PublicBundle\Entity\PerformanceOfAPiece $performances)
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
     * Add practice_parts
     *
     * @param \Icse\MembersBundle\Entity\PracticePart $practiceParts
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
     * @param \Icse\MembersBundle\Entity\PracticePart $practiceParts
     */
    public function removePracticePart(\Icse\MembersBundle\Entity\PracticePart $practiceParts)
    {
        $this->practice_parts->removeElement($practiceParts);
    }

    public function addPrototypePracticePart(\Icse\MembersBundle\Entity\PracticePart $practiceParts)
    {
        $this->practice_parts['__ID__'] = $practiceParts;

        return $this;
    }

    /**
     * Get practice_parts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPracticeParts()
    {
        return $this->practice_parts;
    }

    /**
     * Set updated_by
     *
     * @param \Icse\MembersBundle\Entity\Member $updatedBy
     * @return PieceOfMusic
     */
    public function setUpdatedBy(\Icse\MembersBundle\Entity\Member $updatedBy)
    {
        $this->updated_by = $updatedBy;

        return $this;
    }

    /**
     * Get updated_by
     *
     * @return \Icse\MembersBundle\Entity\Member 
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }
}
