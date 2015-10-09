<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Icse\PublicBundle\Entity\Subscriber
 */
class Subscriber
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $login
     */
    private $login;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $first_name
     */
    private $first_name;

    /**
     * @var string $last_name
     */
    private $last_name;

    /**
     * @var string $department
     */
    private $department;

    /**
     * @var boolean $player
     */
    private $player;

    /**
     * @var string $instrument
     */
    private $instrument;

    private $other_instrument;

    /**
     * @var string $standard
     */
    private $standard;

    /**
     * @var \DateTime $subscribed_at
     */
    private $subscribed_at;


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
     * Set login
     *
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Get Full name; first and last names concatenated together
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->getFirstName() .' '. $this->getLastName();
    }

    /**
     * Set department
     *
     * @param string $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

    /**
     * Get department
     *
     * @return string 
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set player
     *
     * @param boolean $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * Get player
     *
     * @return boolean 
     */
    public function isPlayer()
    {
        return $this->player;
    }

    /**
     * Set instrument
     *
     * @param string $instrument
     */
    public function setInstrument($instrument)
    {
        $this->instrument = $instrument;
    }

    public function setOtherInstrument($instrument)
    {
        $this->other_instrument = $instrument;
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

    public function getOtherInstrument()
    {
        return $this->other_instrument;
    }

    /**
     * Set standard
     *
     * @param string $standard
     */
    public function setStandard($standard)
    {
        $this->standard = $standard;
    }

    /**
     * Get standard
     *
     * @return string 
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * Set subscribed_at
     *
     * @param \DateTime $subscribedAt
     */
    public function setSubscribedAt($subscribedAt)
    {
        $this->subscribed_at = $subscribedAt;
    }

    /**
     * Get subscribed_at
     *
     * @return \DateTime
     */
    public function getSubscribedAt()
    {
        return $this->subscribed_at;
    }

    /**
     * Get player
     *
     * @return boolean 
     */
    public function getPlayer()
    {
        return $this->player;
    }

    public function validatePlayerInfo(ExecutionContextInterface $context)
    {
        if ($this->isPlayer())
        {
            $instruments = $this->getInstrument();
            if (is_string($instruments)) $instruments = explode(', ', $instruments);
            $other_index = array_search('other', $instruments);
            $plays_other_instrument = ($other_index !== false);
            if (!$instruments || ($plays_other_instrument && !$this->getOtherInstrument()))
            {
                $context->buildViolation('Please specify your instrument')->atPath('instrument')->addViolation();
            }
            if ($plays_other_instrument) $instruments[$other_index] = $this->getOtherInstrument();
            $instruments = implode (', ', $instruments);
            $this->setInstrument($instruments);

            if (!$this->getStandard())
            {
                $context->buildViolation('Please indicate your playing standard')->atPath('standard')->addViolation();
            }
        }
        else
        {
            $this->setInstrument(null);
            $this->setStandard(null);
        }
    }
}
