<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Kernel;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Icse\PublicBundle\Entity\Subscriber;
use Common\Tools;

class MiscController extends Controller
{
    public function siteDevAction()
    {
        $last_tick_time = null;
        try
        {
            $membership_product = $this->getDoctrine()
                ->getRepository('IcseMembersBundle:MembershipProduct')
                ->findCurrent();
            if ($membership_product) $last_tick_time = $membership_product->getLastSyncedAt();
        }
        catch (\Exception $e)
        {}

        $symfony_version = Kernel::VERSION;
        $icse_version = @file_get_contents(".revision");
        if ($icse_version === false) $icse_version = "Unknown";
        else                         $icse_version = trim($icse_version);

        $form = $this->createFormBuilder()->getForm();
        return $this->render('IcseMembersBundle:SuperAdmin:sitedev.html.twig', [
            'dummy_form' => $form->createView(),
            'last_tick_time' => $last_tick_time,
            'symfony_version' => $symfony_version,
            'icse_website_version' => $icse_version
        ]);
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

    private function doConsoleCommand($command)
    {
        $kernel = $this->get('kernel');
        $app = new Application($kernel);
        $app->setAutoExit(false);
        $app->setTerminalDimensions(80, 50);

        $input = new ArrayInput(array(
           'command' => $command,
        ));
        $output = new BufferedOutput(
            OutputInterface::VERBOSITY_NORMAL,
            true // true for decorated
        );
        $app->run($input, $output);

        $converter = new AnsiToHtmlConverter();
        $content = $output->fetch();

        return $converter->convert($content);
    }

    public function migrateDBAction()
    {
        if ($this->userIsDeveloper())
        {
            $output = $this->doConsoleCommand('doctrine:migrations:migrate');
            return new Response($output);
        }
        else
        {
            return new Response("Developer mode required; no changes made.");
        }
    }

    public function periodicTickAction()
    {
        if ($this->userIsDeveloper())
        {
            $output = $this->doConsoleCommand('icse:tick');
            return new Response($output);
        }
        else
        {
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

    public function testMailman()
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

    public function testAction()
    {
        try
        {
            $this->get('icse.members_auto_updater')->start();
        }
        catch (\Exception $e)
        {
            return $this->get('ajax_response_gen')->returnFail($e->getMessage());
        }
        return $this->get('ajax_response_gen')->returnSuccess();
    }
}
