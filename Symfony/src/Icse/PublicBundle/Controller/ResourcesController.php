<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Common\Tools;

use Icse\MembersBundle\Entity\PracticePart;

class ResourcesController extends Controller
{
    private $dir = 'Symfony/uploads/';

    private function appendSuffixToFile($path, $suffix)
    {
        $extension_pos = strrpos($path, '.');
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

    private function serveFile($path, $request, $name = null, $download = false)
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
                    $path = $this->resizeImage($path, '_'.$size_id, 100, 100);
                }
                else if ($size_id == 'hpslideshow')
                {
                    $path = $this->resizeImage($path, '_'.$size_id, 334, 254, true);
                }
                else if ($size_id == 'committeeprofile')
                {
                    $path = $this->resizeImage($path, '_'.$size_id, 155, 200, true);
                }
                else if ($size_id == 'hpmainthumb')
                {
                    $path = $this->resizeImage($path, '_'.$size_id, 200, null);
                }
                else if ($size_id == 'hpsidethumb')
                {
                    $path = $this->resizeImage($path, '_'.$size_id, 100, null);
                }
                else if ($size_id == 'hpimagestrip')
                {
                    $path = $this->resizeImage($path, '_'.$size_id, 205, 205);
                }
                else if ($size_id == 'article')
                {
                    $path = $this->resizeImage($path, '_'.$size_id, 380, 380);
                }
                else if ($size_id == 'enlarge')
                {
                    $path = $this->resizeImage($path, '_'.$size_id, 1920, 1920);
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

            if (is_null($name)) $name = basename($path);

            if ($download === true)
            {
                $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $name);
            }
            else
            {
                $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $name);
            }
            $response->headers->set('Content-Disposition', $disposition);

            return $response;
        }
    }

    public function tmpTypeAction($file, Request $request)
    {
        $path = $this->dir . 'tmp/' . $file;
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') == false) {
            throw new AccessDeniedException();
        }
        return $this->serveFile($path, $request);
    }

    public function imagesTypeAction($file, Request $request)
    {
        $path = $this->dir . 'images/' . $file;
        return $this->serveFile($path, $request);
    }

    public function legacypdfTypeAction($file, Request $request)
    {
        $path = $this->dir . 'legacypdf/' . $file;
        if ($this->get('security.context')->isGranted('ROLE_USER') == false) {
            throw new AccessDeniedException();
        }
        return $this->serveFile($path, $request);
    }

    public function practicepartsTypeAction($given_path, Request $request)
    {
        if ($this->get('security.context')->isGranted('ROLE_USER') == false)
        {
            throw new AccessDeniedException();
        }

        /** @var $part PracticePart */
        $part_id = explode('/', $given_path)[0];
        $part = $this->getDoctrine()->getRepository('IcseMembersBundle:PracticePart')->findOneById($part_id);

        if (is_null($part))
        {
            throw $this->createNotFoundException('Practice part doesn\'t exist');
        }

        if ($given_path !== $part->getUrlResourcePath())
        {
            return $this->redirect($this->generateUrl('IcsePublicBundle_autoresource', $part), 301);
        }

        $name = '';
        $piece = $part->getPiece();
        if (!is_null($piece))
        {
            $name .= $piece->getComposer() . ', ';
            $name .= $piece->getName() . ' ';
        }
        $name .= '('.$part->getInstrument().').pdf';

        return $this->serveFile($part->getFilePath(), $request, $name);
    }

    public function resourceAction($type, $file, Request $request)
    {
        $method_name = $type.'TypeAction';
        if (!is_callable([$this, $method_name])) throw $this->createNotFoundException('Invalid Resource Type: '.$type);
        return $this->$method_name($file, $request);
    }

    public function autoAction($resource_type, $url_resource_path, Request $request)
    {
        return $this->resourceAction($resource_type, $url_resource_path, $request);
    }
}
