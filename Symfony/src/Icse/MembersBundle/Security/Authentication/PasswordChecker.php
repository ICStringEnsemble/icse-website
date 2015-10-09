<?php
namespace Icse\MembersBundle\Security\Authentication;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class PasswordChecker
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    private function isImperialPasswordCorrect(UserInterface $user, $raw)
    {
        $username = $user->getUsername();
        if (function_exists('pam_auth')) {
            return pam_auth($username, $raw);
        } else {
            return ($username == "dph10" && $raw == "wibble");
        }
    }

    public function queryPasswordCorrect(UserInterface $user, $raw)
    {
        if ($user->getPassword() === null) {
            return $this->isImperialPasswordCorrect($user, $raw);
        } else {
            return $this->encoder->isPasswordValid($user, $raw);
        }
    }
}
