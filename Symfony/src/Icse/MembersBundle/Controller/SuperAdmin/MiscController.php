<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response; 

use Icse\PublicBundle\Entity\Subscriber; 

class MiscController extends Controller
{
    public function siteDevAction()
    {
        return $this->render('IcseMembersBundle:SuperAdmin:sitedev.html.twig');
    }

    public function migrateDBAction()
    {
        exec('/usr/bin/php ./Symfony/app/console -n doctrine:migrations:migrate', $output, $error);
        if ($error == 0)
        {
          array_push($output, "Success");
        }
        else
        {
          array_push($output, "Fail");
        }
        $pageBody = implode('<br />', $output);
        return new Response($pageBody);
    }

    public function testEmailAction()
    {
        $mailer = $this->get('icse_mailer');

        return $mailer->send(array(
            'template' => 'IcseMembersBundle:Email:temporary_password.html.twig',
            'template_params' => array(
                    'first_name' => 'Joe',
                    'username' => 'joe10',
                    'email' => 'joe.smith@imperial.ac.uk',
                    'password_type' => 'set',
                    'plain_password' => '1234567890',
            ),
            'subject' => 'ICSE Online Account Created', 
            'to' => 'joe.smith@imperial.ac.uk',
            'return_body' => true,
        ));
    } 

    public function testldapAction()
    {
        $username = 'jbh1111';
        $ldap = ldap_connect('addressbook.imperial.ac.uk');
        $search_result = ldap_search($ldap, 'o=Imperial College,c=GB', 'uid=' . $username);
        $entry = ldap_first_entry($ldap, $search_result);
        if ($entry) {
            $attrs = ldap_get_attributes($ldap, $entry); 
            $email = $attrs['mail'][0]; 
            return new Response($email);
        } else {
            return new Response('Does not exist');
        }
    
    }

    public function fixSubscriberEmailsAction()
    {
        $repository = $this->getDoctrine()->getRepository('IcsePublicBundle:Subscriber');
        $em = $this->getDoctrine()->getEntityManager();
        $subscribers = $repository->findAll(); 
        foreach ($subscribers as $s) {
            $login = $s->getLogin();
            if ($login && strpos($s->getEmail(),'@imperial.ac.uk') !== false) {
                $ldap = ldap_connect('addressbook.imperial.ac.uk');
                $search_result = ldap_search($ldap, 'o=Imperial College,c=GB', 'uid=' . $login);
                $entry = ldap_first_entry($ldap, $search_result);
                if ($entry) {
                    $attrs = ldap_get_attributes($ldap, $entry); 
                    $email = $attrs['mail'][0];
                    $s->setEmail($email);
                    $em->persist($s);
                    $em->flush();
                } 
            }
        }
        return new Response('done');
    }

    public function testAction()
    {
        $mm = $this->get('icsepublic_mailman');
//get a list of lists as an array
//print_r($mm->lists());
//echo $mm->unsubscribe('user@example.co.uk');
//echo count($members[0]); 

    var_dump ($mm->members()); 
    //var_dump ($mm->subscribe('user@example.co.uk')); 

        return new Response('hi');
    }
}
