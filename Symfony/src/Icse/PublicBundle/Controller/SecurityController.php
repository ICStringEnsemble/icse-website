<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function loginAction()
    { 
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('IcseMembersBundle_home'));
        } 

        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
          $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
          $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
          $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('IcsePublicBundle:Security:login.html.twig', array(
          // last username entered by the user
          'last_username' => $session->get(SecurityContext::LAST_USERNAME),
          'error'         => $error,
        ));
    }
}
