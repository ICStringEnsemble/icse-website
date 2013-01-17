<?php

namespace Icse\MembersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response; 


class SuperAdminController extends Controller
{
    public function membersListAction()
    {

        $dm = $this->getDoctrine(); 
        $members = $dm->getRepository('IcseMembersBundle:Member')
                     ->findAll();

        $table_columns = array(
                            array('heading' => 'ID', 'cell' => function($member){return $member->getID();}),
                            array('heading' => 'Name', 'cell' => function($member){return $member->getFullName();}),
                            array('heading' => 'Username', 'cell' => function($member){return $member->getUsername();}),
                            array('heading' => 'Email', 'cell' => function($member){return $member->getEmail();}),
                            array('heading' => 'Active', 'cell' => function($member){return $member->getActive();}),
                        );
  
        return $this->render('IcseMembersBundle:SuperAdmin:memberslist.html.twig', array('members' => $members,
                                                                                         'table_columns' => $table_columns));
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
