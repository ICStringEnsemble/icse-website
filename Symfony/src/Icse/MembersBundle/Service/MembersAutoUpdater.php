<?php
namespace Icse\MembersBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ValidatorInterface;
use Common\Tools;
use Icse\MembersBundle\Entity\Member;

class MembersAutoUpdater
{
    private $eactivities;
    private $edit_hooks;
    private $em;
    private $validator;

    private $stats;

    public function __construct(EActivities $eactivities, MembersEditHooks $edit_hooks, EntityManager $em, ValidatorInterface $validator)
    {
        $this->eactivities = $eactivities;
        $this->em = $em;
        $this->validator = $validator;
        $this->edit_hooks = $edit_hooks;
    }

    public function start()
    {
        $n_new = 0;
        $n_updated = 0;

        $repo = $this->em->getRepository('IcseMembersBundle:Member');

        foreach ($this->eactivities->iter_members() as $generated_member)
        {
            /** @var Member $generated_member */
            /** @var Member $member */
            $member = $repo->findOneByUsername($generated_member->getUsername());
            if ($member === null) $member = $repo->findOneByEmail($generated_member->getEmail());
            if ($member === null)
            {
                $member = $generated_member;

                $errors = $this->validator->validate($member);
                if (count($errors) > 0) throw new \Exception((string)$errors);

                $this->em->persist($member);
                $this->em->flush();
                $this->edit_hooks->postCreateMember($member);
                $n_new += 1;
            }
            else
            {
                $member->setLastPaidMembershipOn(max($member->getLastPaidMembershipOn(), $generated_member->getLastPaidMembershipOn()));
                $n_updated += 1;
            }
        }

        $this->em->flush();
        $this->stats = ['new' => $n_new, 'updated' => $n_updated];
    }

    public function stats_string()
    {
        $s = $this->stats;
        return $s['new'] . " new, " . $s['updated'] . " updated";
    }
} 
