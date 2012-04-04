<?php

namespace Icse\MembersBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

if (!function_exists('pam_auth'))
  {
    function pam_auth($username, $password)
    {
      if ($username == "js10" && $password == "wibble")
        {
          return true;
        }
      else
        {
          return false;
        }
    }
  } 

/**
 * uses a UserProviderInterface to retrieve the user
 * for a UsernamePasswordToken.
 */
class ImperialProvider extends \Symfony\Component\Security\Core\Authentication\Provider\UserAuthenticationProvider
{
    private $encoderFactory;
    private $userProvider;

    /**
     * Constructor.
     *
     * @param UserProviderInterface   $userProvider               An UserProviderInterface instance
     * @param UserCheckerInterface    $userChecker                An UserCheckerInterface instance
     * @param string                  $providerKey                The provider key
     * @param EncoderFactoryInterface $encoderFactory             An EncoderFactoryInterface instance
     * @param Boolean                 $hideUserNotFoundExceptions Whether to hide user not found exception or not
     */
    public function __construct(UserProviderInterface $userProvider, UserCheckerInterface $userChecker, $providerKey, EncoderFactoryInterface $encoderFactory, $hideUserNotFoundExceptions = true)
    {
        parent::__construct($userChecker, $providerKey, $hideUserNotFoundExceptions);

        $this->encoderFactory = $encoderFactory;
        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritdoc}
     */
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        $currentUser = $token->getUser();
        if ($currentUser instanceof UserInterface) {
            // not actually implemented properly, but it doesn't seem to care...
            //if (($user->getPassword() != NULL && $currentUser->getPassword() !== $user->getPassword()) || ($user->getPassword() == NULL && $currentUser->getPassword() !== $user->getPassword())) { 
            if (true) { 
                throw new BadCredentialsException('The credentials were changed from another session. (Congratulations, you managed to trigger the mystery exception!)');
            }
        } else {
            if (!$presentedPassword = $token->getCredentials()) {
                throw new BadCredentialsException('No password given');
            }

            if (($user->getPassword() != NULL && !$this->encoderFactory->getEncoder($user)->isPasswordValid($user->getPassword(), $presentedPassword, $user->getSalt()))
            || ($user->getPassword() == NULL && !pam_auth($user->getUsername(), $presentedPassword))) {
                throw new BadCredentialsException('Incorrect password');
            }
        }
    }

    /**
     * {@inheritdoc=
     */
    protected function retrieveUser($username, UsernamePasswordToken $token)
    {
        $user = $token->getUser();
        if ($user instanceof UserInterface) {
            return $user;
        }

        try {
            $user = $this->userProvider->loadUserByUsername($username);

            if (!$user instanceof UserInterface) {
                throw new AuthenticationServiceException('The user provider must return a UserInterface object.');
            }

            return $user;
        } catch (UsernameNotFoundException $notFound) {
            throw $notFound;
        } catch (\Exception $repositoryProblem) {
            throw new AuthenticationServiceException($repositoryProblem->getMessage(), $token, 0, $repositoryProblem);
        }
    }
}
