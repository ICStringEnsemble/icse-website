<?php

namespace Icse\MembersBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Icse\MembersBundle\Entity\Member
 */
class Member implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer $id
     * @Expose
     */
    private $id;

    /**
     * @var string $username
     * @Expose
     */
    private $username;

    /**
     * @var string $salt
     * @Groups({"superadmin"})
     */
    private $salt;

    /**
     * @var string $password
     * @Groups({"superadmin"})
     */
    private $password;

    /**
     * @var integer $password_operation
     * @Groups({"noserialise"})
     */
    private $password_operation;

    /**
     * @var string $plain_password
     * @Groups({"noserialise"})
     */
    private $plain_password;

    /**
     * @var string $email
     * @Groups({"superadmin"})
     */
    private $email;

    /**
     * @var boolean $active
     * @Groups({"superadmin"})
     */
    private $active;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups({"noserialise"})
     */
    private $committee_roles;

    /**
     * @var \Icse\MembersBundle\Entity\MemberProfile
     * @Groups({"noserialise"})
     */
    private $profile;

    /**
     * @var \DateTime
     * @Groups({"superadmin"})
     */
    private $last_paid_membership_on;

    public function __construct()
    {
        $this->committee_roles = new Arraycollection;
        $this->active = true;
        $this->role = self::ROLE_AUTO;
        $this->created_at = new \DateTime();
        $this->password_operation = self::PASSWORD_NO_CHANGE;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(
            array(
                $this->username,
                $this->password,
                $this->salt,
                $this->active
            )
        );
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        list (
            $this->username,
            $this->password,
            $this->salt,
            $this->active,
            ) = unserialize($serialized);
    }

    private function getAutoRole(\DateTime $dt = null)
    {
        if (is_null($dt)) $dt = new \DateTime();
        $current_month = intval($dt->format("m"));
        $current_year = intval($dt->format("Y"));

        if ($current_month < 6)  $required_start_year = [$current_year - 1];
        elseif ($current_month < 10) $required_start_year = [$current_year - 1, $current_year];
        else $required_start_year = [$current_year];

        $current_roles = $this->getCommitteeRolesMatchingYears($required_start_year);

        if ($current_roles->count()) return ['ROLE_ADMIN'];
        else return ['ROLE_USER'];
    }


    const ROLE_AUTO = 1;
    const ROLE_ADMIN = 10;
    const ROLE_SUPER_ADMIN = 100;

    public function getRoles(\DateTime $dt = null)
    {
        switch($this->getRole())
        {
            case self::ROLE_AUTO:
                return $this->getAutoRole($dt);
            case self::ROLE_ADMIN:
                return ['ROLE_ADMIN'];
            case self::ROLE_SUPER_ADMIN:
                return ['ROLE_SUPER_ADMIN'];
            default:
                return ['ROLE_USER'];
        }
    }

    public function equals(UserInterface $member)
    {
        return $member->getUsername() === $this->username;
    }

    public function eraseCredentials()
    {
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive();
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
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
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
     * Set active
     *
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }
    /**
     * @var string $first_name
     */
    private $first_name;

    /**
     * @var string $last_name
     */
    private $last_name;


    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
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
     * @var integer $role
     * @Groups({"superadmin"})
     */
    private $role;


    /**
     * Set role
     *
     * @param integer $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get role
     *
     * @return integer
     */
    public function getRole()
    {
        return $this->role;
    }
    /**
     * @var \DateTime
     * @Groups({"superadmin"})
     */
    private $created_at;


    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Member
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    /**
     * @var \DateTime
     * @Groups({"superadmin"})
     */
    private $last_online_at;


    /**
     * Set last_online_at
     *
     * @param \DateTime $lastOnlineAt
     * @return Member
     */
    public function setLastOnlineAt($lastOnlineAt)
    {
        $this->last_online_at = $lastOnlineAt;

        return $this;
    }

    /**
     * Get last_online_at
     *
     * @return \DateTime
     */
    public function getLastOnlineAt()
    {
        return $this->last_online_at;
    }

    /**
     * Add committee_roles
     *
     * @param \Icse\MembersBundle\Entity\CommitteeRole $committeeRoles
     * @return Member
     */
    public function addCommitteeRole(\Icse\MembersBundle\Entity\CommitteeRole $committeeRoles)
    {
        $this->committee_roles[] = $committeeRoles;

        return $this;
    }

    /**
     * Remove committee_roles
     *
     * @param \Icse\MembersBundle\Entity\CommitteeRole $committeeRoles
     */
    public function removeCommitteeRole(\Icse\MembersBundle\Entity\CommitteeRole $committeeRoles)
    {
        $this->committee_roles->removeElement($committeeRoles);
    }

    /**
     * Get committee_roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommitteeRoles()
    {
        return $this->committee_roles;
    }

    public function getCommitteeRolesMatchingYears($years)
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->in('start_year', $years));

        return $this->committee_roles->matching($criteria);
    }

    /**
     * Set profile
     *
     * @param \Icse\MembersBundle\Entity\MemberProfile $profile
     * @return Member
     */
    public function setProfile(\Icse\MembersBundle\Entity\MemberProfile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Icse\MembersBundle\Entity\MemberProfile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    public function getMostCurrentCommitteeRole(\DateTime $dt = null)
    {
        if (is_null($dt)) $dt = new \DateTime();
        $current_month = intval($dt->format("m"));
        $current_year = intval($dt->format("Y"));

        if ($current_month < 8)
        {
            $start_year1 = $current_year - 1;
            $start_year2 = $current_year;
        }
        else
        {
            $start_year1 = $current_year;
            $start_year2 = $current_year - 1;
        }

        foreach ([$start_year1, $start_year2] as $y)
        {
            $roles = $this->getCommitteeRolesMatchingYears([$y]);
            if (count($roles) > 0)
            {
                return $roles[0];
            }
        }

        return null;
    }

    /**
     * Set last_paid_membership_on
     *
     * @param \DateTime $lastPaidMembershipOn
     * @return Member
     */
    public function setLastPaidMembershipOn($lastPaidMembershipOn)
    {
        $this->last_paid_membership_on = $lastPaidMembershipOn;

        return $this;
    }

    /**
     * Get last_paid_membership_on
     *
     * @return \DateTime 
     */
    public function getLastPaidMembershipOn()
    {
        return $this->last_paid_membership_on;
    }

    public function hasCurrentMembership($dt = null)
    {
        $last_paid = $this->getLastPaidMembershipOn();
        if ($last_paid === null) return false;

        if (is_null($dt)) $dt = new \DateTime;

        $last_august = new \DateTime("1st August");
        while ($last_august > $dt) $last_august->sub(new \DateInterval("P1Y"));

        return $last_paid >= $last_august;
    }

    const PASSWORD_NO_CHANGE = 0;
    const PASSWORD_IMPERIAL = 1;
    const PASSWORD_RANDOM = 2;
    const PASSWORD_SET = 3;

    /**
     * @return integer
     */
    public function getPasswordOperation()
    {
        return $this->password_operation;
    }

    /**
     * @param integer $password_operation
     * @return Member
     */
    public function setPasswordOperation($password_operation)
    {
        $this->password_operation = $password_operation;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plain_password;
    }

    /**
     * @param string $plain_password
     * @return Member
     */
    public function setPlainPassword($plain_password)
    {
        $this->plain_password = $plain_password;
        return $this;
    }

    public function validate(ExecutionContextInterface $context)
    {
        if ($this->getPasswordOperation() == self::PASSWORD_SET) {
            $len = strlen($this->getPlainPassword());
            $error = "";
            if      ($len <   8) $error = 'Your new password must be at least 8 characters';
            else if ($len > 999) $error = 'Your new password must be less than 1000 characters';

            if ($error) $context->buildViolation($error)
                ->atPath('plain_password')
                ->addViolation();
        }
    }
}
