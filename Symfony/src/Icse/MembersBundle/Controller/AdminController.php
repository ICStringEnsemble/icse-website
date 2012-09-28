<?php

namespace Icse\MembersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response; 
use Icse\MembersBundle\Form\Type\FileInfoType;
use Icse\PublicBundle\Entity\Image;


class AdminController extends Controller
{
  private $tmp_dir = 'Symfony/uploads/tmp/';

  public function imageUploadsAction()
    {
      $form = $this->createForm(new FileInfoType(), new Image());

      return $this->render('IcseMembersBundle:Admin:images.html.twig', array('form' => $form->createView())); 
    }

  public function uploadAction(Request $request)
    {
      $files = $request->files->get('files'); 

      $new_names = array();
      if ($files)
        {
          foreach ($files as $file)
            {
              $extension = $file->guessExtension();
              if (!$extension)
                {
                  $extension = 'bin';
                }
              $new_filename = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36).'.'.$extension;
              $file->move($this->tmp_dir, $new_filename);
              chmod($this->tmp_dir.$new_filename, 0660);
              array_push($new_names, $new_filename);
            }
        }

      $pageBody = 'done';
      return new Response(json_encode($new_names));
    }

  public function confirmUploadAction(Request $request)
    {
      if ($request->getMethod() == 'POST')
        {
          $form_data = $this->get('request')->request->get('file');
          $stored_filename = $form_data['file'];
          if ($stored_filename)
            {
              if (getimagesize($this->tmp_dir.$stored_filename)) $file = new Image();
              else $file = new Image();

              $form = $this->createForm(new FileInfoType(), $file);
              $form->bind($request); 
              if ($form->isValid())
                {
                  $file->setUpdatedAt(new \DateTime());
                  $file->setUpdatedBy($this->get('security.context')->getToken()->getUser()->getId()); 

                  $em = $this->getDoctrine()->getManager();
                  $em->persist($file);
                  $em->flush(); 
                  return new Response(json_encode("success"));
                }
            }
        } 

      return new Response('error');
    }
}
