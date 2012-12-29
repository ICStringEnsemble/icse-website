<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Icse\PublicBundle\Entity\Venue
 */
class Venue
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
     * @return Venue
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
     * @var string
     */
    private $embedMap;


    /**
     * Set embedMap
     *
     * @param string $embedMap
     * @return Venue
     */
    public function setEmbedMap($embedMap)
    {
        $this->embedMap = $embedMap;
    
        return $this;
    }

    /**
     * Get embedMap
     *
     * @return string 
     */
    public function getEmbedMap()
    {
        return $this->embedMap;
    }
    /**
     * @var string
     */
    private $address;


    /**
     * Set address
     *
     * @param string $address
     * @return Venue
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }
}