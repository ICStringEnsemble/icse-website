<?php

namespace Icse\MembersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

function randString($length) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
	$size = strlen( $chars );
  $str = "";
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[mt_rand(0, $size-1)];
	}
	return $str;
} 

class AccountSettingsController extends Controller
{

  private function isValidEmail($input)
    {
      $emailConstraint = new \Symfony\Component\Validator\Constraints\Email;
      $emailConstraint->checkMX = true;
      $errorList = $this->get('validator')->validateValue($input, $emailConstraint); 
      return count($errorList) == 0; 
    }
   
  public function indexAction(Request $request)
    {
      $cpResponse = array();
      $ceResponse = array();
      $user = $this->get('security.context')->getToken()->getUser(); 

      if ($request->request->get('form_id') == "cp") { //if change password
        $encoder = $this->get('security.encoder_factory'); 
        if (!\Icse\MembersBundle\Security\Authentication\Provider\queryCredsValid($user,
                                                                                  $request->request->get('old_password'),
                                                                                  $encoder)) { //if old password incorrect
          $cpResponse['oldpass'] = "Incorrect password";
        } elseif ($request->request->get('icse_passwd') == null) {
          $cpResponse['icsepass'] = "Please specify";
        } elseif ($request->request->get('icse_passwd') == 1) {
          $user->setPassword(null);
          $user->setSalt(null);
          $cpResponse['success'] = "Password was sucessfully changed";
        } elseif ($request->request->get('new_password') != $request->request->get('new_password_again')) {
          $cpResponse['newpass'] = "Passwords don't match";
        } elseif (strlen($request->request->get('new_password')) < 8) {
          $cpResponse['newpass'] = "Password must be at least 8 characters";
        } else {
          $user->setSalt(randString(40));
          $pass_hash = $encoder->getEncoder($user)->encodePassword($request->request->get('new_password'), $user->getSalt());
          $user->setPassword($pass_hash);
          $cpResponse['success'] = "Password was sucessfully changed";
        }

        if (isset($cpResponse['success'])) {
          $em = $this->getDoctrine()->getEntityManager();
          $em->persist($user);
          $em->flush();
        } else {
          $cpResponse['passtype'] = $request->request->get('icse_passwd');
        }
      }
      
      else if ($request->request->get('form_id') == "ce") { //change email
        if ($this->isValidEmail($request->request->get('new_email'))) {
          $user->setEmail($request->request->get('new_email'));
          $em = $this->getDoctrine()->getEntityManager();
          $em->persist($user);
          $em->flush();
          $ceResponse['success'] = "Email address was successfully changed";
        } else {
          $ceResponse['newemail'] = "Invalid email address";
        }
      }

      $ImperialPasswd = !($user->getPassword());
      $email = $user->getEmail();
      return $this->render('IcseMembersBundle:AccountSettings:index.html.twig', array("ImperialPasswd" => $ImperialPasswd,
                                                                                      "email" => $email, 
                                                                                      "ceResponse" => $ceResponse, 
                                                                                      "cpResponse" => $cpResponse));
    }
}
