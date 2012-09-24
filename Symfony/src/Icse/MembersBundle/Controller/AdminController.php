<?php

namespace Icse\MembersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 


class AdminController extends Controller
{
  public function imageUploadsAction()
    {
      return $this->render('IcseMembersBundle:Admin:images.html.twig', array()); 
    }

  public function uploadAction()
    {
      $request = Request::createFromGlobals(); 
      $files = $request->files->get('images'); 

      foreach ($files as $file)
        {
          $file->move('Symfony/uploads/tmp/', $file->getClientOriginalName());
        }

      $pageBody = 'done';
      return $this->render('IcseMembersBundle:Default:generic_page.html.twig', array('pageTitle' => 'Image Upload',
                                                                                     'pageBody' => $pageBody )); 
    }
}
