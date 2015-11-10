<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\LockedException;


class SecurityController extends Controller
{
    public function loginAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('IcseMembersBundle_home'));
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        $username = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        $creds_error   = ($error instanceof BadCredentialsException);
        $locked_error  = ($error instanceof LockedException);
        $expired_error = ($error instanceof AccountExpiredException);

        return $this->render('IcsePublicBundle:Security:login.html.twig', [
          'last_username' => $username,
          'creds_error'   => $creds_error,
          'locked_error'  => $locked_error,
          'expired_error' => $expired_error,
        ]);
    }
}
