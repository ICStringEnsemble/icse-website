<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\ResponseHeaderBag; 
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser; 
use Symfony\Component\Security\Core\Exception\AccessDeniedException; 

class ResourcesController extends Controller
{
  private $dir = 'Symfony/uploads/'; 

  private function appendSuffixToFile($path, $suffix)
    {
      $extension_pos = strrpos($path, '.'); // find position of the last dot
      return substr($path, 0, $extension_pos) . $suffix . substr($path, $extension_pos); 
    }

  private function resizeImage ($path, $dest_suffix, $width, $height, $crop=false)
    {
      $dest_path = $this->appendSuffixToFile($path, $dest_suffix);
      if (!file_exists($dest_path))
        {
          $image = $this->get('fkr_imagine')->open($path);

          $origSize = $image->getSize();
          if ($height === null)
            {
              $height = (int)(($width / $origSize->getWidth()) * $origSize->getHeight());
            }
          else if ($width === null)
            {
              $width = (int)(($height / $origSize->getHeight()) * $origSize->getWidth());
            }
          $size = new \Imagine\Image\Box($width, $height);

          if ($crop)
              $mode = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
          else
              $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;

          $image->thumbnail($size, $mode)->save($dest_path); 
          chmod($dest_path, 0660);
        }
      return $dest_path;
    }

  private function serveFile($path, $request, $download = false)
    {
      if (!file_exists($path))
        {
          throw $this->createNotFoundException('File Doesn\'t exist: '.$path);
        }
      else
        {
          if($request->query->has('size') && getimagesize($path))
            {
              $size_id = $request->query->get('size');
              $original_path = $path;

              if ($size_id == 'thumb')
                {
                  $path = $this->resizeImage($path, '_thumb', 100, 100);
                }
              else if ($size_id == 'original')
                {
                }
              else
                {
                  throw $this->createNotFoundException('Invalid size: '.$size_id);
                }
            }
          $response = new Response(); 
          $response->setContent(file_get_contents($path));

          $mime_guesser = MimeTypeGuesser::getInstance();
          $mime_type = $mime_guesser->guess($path);
          $response->headers->set('content-type', $mime_type);

          if ($download === true)
            {
              $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, basename($path));
            }
          else
            {
              $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, basename($path));
            }
          $response->headers->set('Content-Disposition', $disposition); 

          return $response;
        }
    }

  public function resourceAction($type, $file, Request $request)
    {
      $path = $this->dir . 'tmp/' . $file;
      if ($type == 'tmp')
        {
          if ($this->get('security.context')->isGranted('ROLE_ADMIN') == false)
            {
              throw new AccessDeniedException();
            } 
          return $this->serveFile($path, $request);
        }
      else
        {
          throw $this->createNotFoundException('Invalid Resource Type: '.$type);
        }
    }
}
