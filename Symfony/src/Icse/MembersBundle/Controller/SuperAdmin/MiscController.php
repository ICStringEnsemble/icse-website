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
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('send@example.com')
            ->setTo('dphoyes@gmail.com')
            ->setBody(
                'hello world'
            )
        ;
        $this->get('mailer')->send($message);
     
        return new Response('hi');
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
}
