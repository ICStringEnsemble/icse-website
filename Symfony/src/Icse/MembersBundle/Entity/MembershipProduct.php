<?php

namespace Icse\MembersBundle\Entity;

/**
 * MembershipProduct
 */
class MembershipProduct
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $starts_at;

    /**
     * @var \DateTime
     */
    private $ends_at;

    /**
     * @var string
     */
    private $price;

    /**
     * @var string
     */
    private $url;
    
    /**
     * @var \DateTime
     */
    private $last_synced_at;

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return MembershipProduct
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set starts_at
     *
     * @param \DateTime $starts_at
     *
     * @return MembershipProduct
     */
    public function setStartsAt($starts_at)
    {
        $this->starts_at = $starts_at;

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
     * Set ends_at
     *
     * @param \DateTime $ends_at
     *
     * @return MembershipProduct
     */
    public function setEndsAt($ends_at)
    {
        $this->ends_at = $ends_at;

        return $this;
    }

    /**
     * Get ends_at
     *
     * @return \DateTime
     */
    public function getEndsAt()
    {
        return $this->ends_at;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return MembershipProduct
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return MembershipProduct
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set lastSyncedAt
     *
     * @param \DateTime $lastSyncedAt
     *
     * @return MembershipProduct
     */
    public function setLastSyncedAt($lastSyncedAt)
    {
        $this->last_synced_at = $lastSyncedAt;

        return $this;
    }

    /**
     * Get lastSyncedAt
     *
     * @return \DateTime
     */
    public function getLastSyncedAt()
    {
        return $this->last_synced_at;
    }
}
