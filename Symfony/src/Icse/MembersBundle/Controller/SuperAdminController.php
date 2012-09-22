<?php

namespace Icse\MembersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SuperAdminController extends Controller
{
  public function migrateDBAction()
    {
      exec('echo y | /usr/bin/php ./Symfony/app/console doctrine:migrations:migrate', $output, $error);
      if ($error == 0)
        {
          array_push($output, "Success");
        }
      else
        {
          array_push($output, "Fail");
        }
      $pageBody = "<h1>Database Migration</h1>" . implode('<br />', $output);
      return $this->render('IcseMembersBundle:Default:generic_page.html.twig', array('pageTitle' => 'Migrate Database',
                                                                                     'pageBody' => $pageBody));
    }
}
