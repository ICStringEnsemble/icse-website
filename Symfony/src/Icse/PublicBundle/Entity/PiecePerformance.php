<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Icse\PublicBundle\Entity\PiecePerformance
 */
class PiecePerformance
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
}
