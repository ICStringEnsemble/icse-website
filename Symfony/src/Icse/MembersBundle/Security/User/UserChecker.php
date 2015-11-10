<?php

namespace Icse\MembersBundle\Security\User;

use Symfony\Component\Security\Core\User\UserChecker as SymfonyChecker;
use Symfony\Component\Security\Core\User\UserInterface;


class UserChecker extends SymfonyChecker
{
    /**
     * {@inheritdoc}
     */
    public function checkPreAuth(UserInterface $user)
    {
        // Don't check expiredness till after authentication
    }

    /**
     * {@inheritdoc}
     */
    public function checkPostAuth(UserInterface $user)
    {
        parent::checkPreAuth($user);
        parent::checkPostAuth($user);
    }
}
