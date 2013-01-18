<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Common\Tools;  

/**
 * NewsArticle
 */
class NewsArticle
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $headline;

    /**
     * @var string
     */
    private $subhead;

    /**
     * @var string
     */
    private $body;

    /**
     * @var \DateTime
     */
    private $posted_at;

    /**
     * @var \DateTime
     */
    private $updated_at;


    /**
     * @var \Icse\PublicBundle\Entity\Image
     */
    private $picture;

    /**
     * @var \Icse\MembersBundle\Entity\Member
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
     * Set headline
     *
     * @param string $headline
     * @return NewsArticle
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;
    
        return $this;
    }

    /**
     * Get headline
     *
     * @return string 
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * Set subhead
     *
     * @param string $subhead
     * @return NewsArticle
     */
    public function setSubhead($subhead)
    {
        $this->subhead = $subhead;
    
        return $this;
    }

    /**
     * Get subhead
     *
     * @return string 
     */
    public function getSubhead()
    {
        return $this->subhead;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return NewsArticle
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set posted_at
     *
     * @param \DateTime $postedAt
     * @return NewsArticle
     */
    public function setPostedAt($postedAt)
    {
        $this->posted_at = $postedAt;
    
        return $this;
    }

    /**
     * Get posted_at
     *
     * @return \DateTime 
     */
    public function getPostedAt()
    {
        return $this->posted_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return NewsArticle
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
     * @return NewsArticle
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updated_by = $updatedBy;
    
        return $this;
    }



    /**
     * Set picture
     *
     * @param \Icse\PublicBundle\Entity\Image $picture
     * @return NewsArticle
     */
    public function setPicture(\Icse\PublicBundle\Entity\Image $picture = null)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return \Icse\PublicBundle\Entity\Image 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    public function getSlug()
    {
        return Tools::slugify($this->getHeadline());
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