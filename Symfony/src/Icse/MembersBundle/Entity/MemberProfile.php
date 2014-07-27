<?php

namespace Icse\MembersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Icse\PublicBundle\Entity\Image;

/**
 * MemberProfile
 */
class MemberProfile
{
    /**
     * @var string
     */
    private $instrument;

    /**
     * @var \Icse\MembersBundle\Entity\Member
     */
    private $member;


    /**
     * Set instrument
     *
     * @param string $instrument
     * @return MemberProfile
     */
    public function setInstrument($instrument)
    {
        $this->instrument = $instrument;

        return $this;
    }

    /**
     * Get instrument
     *
     * @return string 
     */
    public function getInstrument()
    {
        return $this->instrument;
    }

    /**
     * Set member
     *
     * @param \Icse\MembersBundle\Entity\Member $member
     * @return MemberProfile
     */
    public function setMember(\Icse\MembersBundle\Entity\Member $member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \Icse\MembersBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }
    /**
     * @var integer
     */
    private $join_year;

    /**
     * @var string
     */
    private $study_subject;

    /**
     * @var string
     */
    private $favourite_snack;

    /**
     * @var string
     */
    private $memorable_moment;

    /**
     * @var Image
     */
    private $picture;


    /**
     * Set join_year
     *
     * @param integer $joinYear
     * @return MemberProfile
     */
    public function setJoinYear($joinYear)
    {
        $this->join_year = $joinYear;

        return $this;
    }

    /**
     * Get join_year
     *
     * @return integer 
     */
    public function getJoinYear()
    {
        return $this->join_year;
    }

    /**
     * Set study_subject
     *
     * @param string $studySubject
     * @return MemberProfile
     */
    public function setStudySubject($studySubject)
    {
        $this->study_subject = $studySubject;

        return $this;
    }

    /**
     * Get study_subject
     *
     * @return string 
     */
    public function getStudySubject()
    {
        return $this->study_subject;
    }

    /**
     * Set favourite_snack
     *
     * @param string $favouriteSnack
     * @return MemberProfile
     */
    public function setFavouriteSnack($favouriteSnack)
    {
        $this->favourite_snack = $favouriteSnack;

        return $this;
    }

    /**
     * Get favourite_snack
     *
     * @return string 
     */
    public function getFavouriteSnack()
    {
        return $this->favourite_snack;
    }

    /**
     * Set memorable_moment
     *
     * @param string $memorableMoment
     * @return MemberProfile
     */
    public function setMemorableMoment($memorableMoment)
    {
        $this->memorable_moment = $memorableMoment;

        return $this;
    }

    /**
     * Get memorable_moment
     *
     * @return string 
     */
    public function getMemorableMoment()
    {
        return $this->memorable_moment;
    }

    /**
     * Set picture
     *
     * @param Image $picture
     * @return MemberProfile
     */
    public function setPicture(Image $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return Image
     */
    public function getPicture()
    {
        return $this->picture;
    }
}
