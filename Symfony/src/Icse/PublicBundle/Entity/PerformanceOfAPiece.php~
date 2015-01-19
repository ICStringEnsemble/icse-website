<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Icse\PublicBundle\Entity\PieceOfMusic;
use Icse\PublicBundle\Entity\Event;

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
     * @var Event
     */
    private $event;

    /**
     * @var PieceOfMusic
     */
    private $piece;

    /**
     * @var integer
     */
    private $sort_index;


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
     * @param PieceOfMusic $piece
     * @return PerformanceOfAPiece
     */
    public function setPiece(PieceOfMusic $piece = null)
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * Get piece
     *
     * @return PieceOfMusic
     */
    public function getPiece()
    {
        return $this->piece;
    }

    /**
     * Set event
     *
     * @param Event $event
     * @return PerformanceOfAPiece
     */
    public function setEvent(Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set sort_index
     *
     * @param integer $sortIndex
     * @return PerformanceOfAPiece
     */
    public function setSortIndex($sortIndex)
    {
        $this->sort_index = $sortIndex;

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
