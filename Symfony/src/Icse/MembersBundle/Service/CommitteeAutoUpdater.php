<?php
namespace Icse\MembersBundle\Service;

use Doctrine\ORM\EntityManager;
use Icse\MembersBundle\Entity\CommitteeRole;

class CommitteeAutoUpdater
{
    private $eactivities;
    private $em;

    private $stats;

    public function __construct(EActivities $eactivities, EntityManager $em)
    {
        $this->eactivities = $eactivities;
        $this->em = $em;
        $this->stats = [];
    }

    public function start(\DateTime $dt = null)
    {
        if ($dt === null) $dt = new \DateTime;
        $current_year = intval($dt->format("Y"));

        $role_repo   = $this->em->getRepository('IcseMembersBundle:CommitteeRole');
        $member_repo = $this->em->getRepository('IcseMembersBundle:Member');

        foreach ([$current_year - 1, $current_year] as $y)
        {
            $n_new = 0;
            $n_assumed_preexisting = 0;
            $n_needing_membership = 0;

            $year_str = ($y%100)."-".(($y+1)%100);
            $next_sort_index = $role_repo->getMaxSortIndexForYear($y) + 1;

            $roles = $this->eactivities->get_committee_members($year_str);

            /** @var array $generated_role */
            foreach ($roles as $login => $generated_roles)
            {
                $existing_roles = $role_repo->findByUsernameAndYear($login, $y);
                if (!empty($existing_roles))
                {
                    $n_assumed_preexisting += count($generated_roles);
                    continue;
                }

                $member = $member_repo->findOneByUsername($login);
                if ($member === null)
                {
                    $n_needing_membership += count($generated_roles);
                    continue;
                }

                /** @var CommitteeRole $role */
                foreach ($generated_roles as $role)
                {
                    $role->setMember($member);
                    $role->setSortIndex($next_sort_index);
                    $next_sort_index++;

                    $this->em->persist($role);
                    $this->em->flush();
                    $n_new += 1;
                }
            }

            $this->stats[$year_str] = ['new' => $n_new, 'preexist' => $n_assumed_preexisting, 'need_member' => $n_needing_membership];
        }
    }

    public function iter_stats_keys()
    {
        foreach ($this->stats as $year_str => $_) yield $year_str;
    }

    public function stats_string($year_str)
    {
        $s = $this->stats[$year_str];
        return $s['new'] . " new, " . $s['preexist'] . " pre-exist, " . $s['need_member'] . " need membership";
    }
} 
