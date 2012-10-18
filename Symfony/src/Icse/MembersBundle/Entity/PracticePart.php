<?php

namespace Icse\MembersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Icse\MembersBundle\Entity\PracticePart
 */
class PracticePart
{
    /**
     * @var integer $id
     */
    private $id;


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
     * @var string $file
     */
    private $file;

    /**
     * @var string $type
     */
    private $type;

    /**
     * @var \DateTime $updated_at
     */
    private $updated_at;

    /**
     * @var integer $updated_by
     */
    private $updated_by;

    /**
     * @var Icse\PublicBundle\Entity\PieceOfMusic
     */
    private $piece;


    /**
     * Set file
     *
     * @param string $file
     * @return PracticePart
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return PracticePart
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return PracticePart
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
     * @return PracticePart
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
     * Set piece
     *
     * @param Icse\PublicBundle\Entity\PieceOfMusic $piece
     * @return PracticePart
     */
    public function setPiece(\Icse\PublicBundle\Entity\PieceOfMusic $piece = null)
    {
        $this->piece = $piece;
    
        return $this;
    }

    /**
     * Get piece
     *
     * @return Icse\PublicBundle\Entity\PieceOfMusic 
     */
    public function getPiece()
    {
        return $this->piece;
    }
}