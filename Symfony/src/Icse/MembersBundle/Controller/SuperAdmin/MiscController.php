<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Icse\PublicBundle\Entity\Subscriber;
use Icse\PublicBundle\Entity\Image;
use Common\Tools;

class MiscController extends Controller
{
    public function siteDevAction()
    {
        $form = $this->createFormBuilder()->getForm();
        return $this->render('IcseMembersBundle:SuperAdmin:sitedev.html.twig', ['dummy_form' => $form->createView()]);
    }

    private function userIsDeveloper()
    {
        return $this->get('kernel')->getEnvironment() == 'dev' or $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
    }

    public function phpinfoAction()
    {
        if ($this->userIsDeveloper())
        {
            phpinfo();
            return new Response;
        }
        else
        {
            return new Response("You are not a developer.");
        }
    }

    public function appcacheAction()
    {
        return new Response('<html manifest="/appcache"><body>Link .appcache</body></html>');
    }

    public function migrateDBAction()
    {
        if ($this->userIsDeveloper()) {
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
        } else {
            return new Response("Developer mode required; no changes made.");
        }
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
            /* @var $s Subscriber */
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

    public function zipDirAction($source_path)
    {
        if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
            set_time_limit(0);
            $zip_name = Tools::slugify($source_path) . '.zip';
            $zip_path = 'Symfony/uploads/' . $zip_name;
            $root = $_SERVER['DOCUMENT_ROOT'];
            exec("/usr/bin/zip -r $root/$zip_path $root/$source_path", $output, $error);
            array_push($output, $error == 0 ? "Success" : "Fail");
            $page_body = implode('<br />', $output);
            return new Response($page_body);
        }
        else
        {
            return $this->createAccessDeniedException();
        }
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
