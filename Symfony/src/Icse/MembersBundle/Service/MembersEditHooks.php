<?php
namespace Icse\MembersBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator;
use Common\Tools;
use Icse\MembersBundle\Entity\Member;

class MembersEditHooks
{
    private $mailer;

    public function __construct(IcseMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    private function sendNewAccountEmail(Member $member)
    {
        $this->mailer->setTemplate('IcseMembersBundle:Email:account_created.html.twig')
            ->setBodyFields(['member' => $member])
            ->setSubject('ICSE Online Account Created')
            ->send($member->getEmail(), $member->getFirstName());
    }

    private function sendTempPasswordEmail(Member $member)
    {
        $this->mailer->setTemplate('IcseMembersBundle:Email:temporary_password.html.twig')
            ->setBodyFields(['member' => $member])
            ->setSubject('ICSE Account Password Reset')
            ->send($member->getEmail(), $member->getFirstName());
    }

    public function postCreateMember(Member $member)
    {
        $this->sendNewAccountEmail($member);
    }

    public function postEditMember(Member $member)
    {
        if ($member->getPasswordOperation() == Member::PASSWORD_RANDOM)
        {
            $this->sendTempPasswordEmail($member);
        }
    }

} 
