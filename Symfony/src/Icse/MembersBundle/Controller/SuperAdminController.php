<?php

namespace Icse\MembersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response; 


class SuperAdminController extends Controller
{
    public function membersListAction()
    {
        return $this->render('IcseMembersBundle:SuperAdmin:memberslist.html.twig');
    }

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
}
