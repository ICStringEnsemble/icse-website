<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Icse\PublicBundle\Entity\PerformanceOfAPiece
 */
class PerformanceOfAPiece
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var Icse\PublicBundle\Entity\PieceOfMusic
     */
    private $piece;


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
     * Set piece
     *
     * @param Icse\PublicBundle\Entity\PieceOfMusic $piece
     * @return PerformanceOfAPiece
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
    /**
     * @var Icse\PublicBundle\Entity\Event
     */
    private $event;


    /**
     * Set event
     *
     * @param Icse\PublicBundle\Entity\Event $event
     * @return PerformanceOfAPiece
     */
    public function setEvent(\Icse\PublicBundle\Entity\Event $event = null)
    {
        $this->event = $event;
    
        return $this;
    }

    /**
     * Get event
     *
     * @return Icse\PublicBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }
}
