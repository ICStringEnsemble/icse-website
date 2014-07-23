<?php

namespace Icse\MembersBundle\Entity;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * Icse\MembersBundle\Entity\Member
 */
class Member implements AdvancedUserInterface
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
     * @Groups({"collection"})
     */
    private $committee_roles;

    public function __construct()
    {
      $this->active = true;
      $this->role = 1;
    }

    private function getAutoRole($dt = null)
    {
        if (is_null($dt)) $dt = new \DateTime();
        $current_month = intval($dt->format("m"));
        $current_year = intval($dt->format("Y"));

        if ($current_month < 6)  $required_start_year = [$current_year - 1];
        elseif ($current_month < 10) $required_start_year = [$current_year - 1, $current_year];
        else $required_start_year = [$current_year];

        $current_roles = $this->getCommitteeRolesMatchingYears($required_start_year);

        if ($current_roles->count()) return array('ROLE_ADMIN');
        else return array('ROLE_USER');
    }

    public function getRoles($dt = null)
    {
      switch($this->getRole()) {
        case 1:
          return $this->getAutoRole($dt);
        case 10:
          return array('ROLE_ADMIN');
        case 100:
          return array('ROLE_SUPER_ADMIN');
        default:
          return array('ROLE_USER');
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
}
