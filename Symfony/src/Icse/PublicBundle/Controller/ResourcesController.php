<?php

namespace Icse\PublicBundle\Controller;

use Imagine\Image\Box as ImagineBox;
use Imagine\Image\ImageInterface as ImagineInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Icse\PublicBundle\Entity\Image;
use Icse\PublicBundle\Entity\Interfaces\ResourceInterface;
use Common\Tools;

use Icse\MembersBundle\Entity\PracticePart;

class ResourcesController extends Controller
{
    private $dir = 'Symfony/uploads/';

    private function resizeImage ($path, $dest_suffix, $width, $height, $crop=false)
    {
        $dest_dir = $this->container->get( 'kernel' )->getCacheDir() . '/icse_resized_images';
        $fs = $this->container->get('filesystem');
        $fs->mkdir($dest_dir);

        $path_parts = pathinfo($path);
        $dest_path = $dest_dir . '/' . $path_parts['filename'] . '_' . $dest_suffix . '.' .  $path_parts['extension'];

        if (!$fs->exists($dest_path))
        {
            $image = $this->get('fkr_imagine')->open($path);

            $orig_size = $image->getSize();
            $orig_ratio = $orig_size->getHeight() / $orig_size->getWidth();
            if ($height === null) $height = $width * $orig_ratio;
            else if ($width === null) $width = $height / $orig_ratio;
            $size = new ImagineBox((int)$width, (int)$height);

            $mode = $crop ? ImagineInterface::THUMBNAIL_OUTBOUND : ImagineInterface::THUMBNAIL_INSET;
            $image->thumbnail($size, $mode)->save($dest_path);
            $fs->chmod($dest_path, 0660);
        }
        return $dest_path;
    }

    private function handleImageResizeRequest($path, Request $request)
    {
        if ($request->query->has('size'))
        {
            $size_id = $request->query->get('size');

            if ($size_id == 'thumb')
            {
                $path = $this->resizeImage($path, $size_id, 100, 100);
            }
            else if ($size_id == 'hpslideshow')
            {
                $path = $this->resizeImage($path, $size_id, 334, 254, true);
            }
            else if ($size_id == 'committeeprofile')
            {
                $path = $this->resizeImage($path, $size_id, 155, 200, true);
            }
            else if ($size_id == 'hpmainthumb')
            {
                $path = $this->resizeImage($path, $size_id, 200, null);
            }
            else if ($size_id == 'hpsidethumb')
            {
                $path = $this->resizeImage($path, $size_id, 100, null);
            }
            else if ($size_id == 'hpimagestrip')
            {
                $path = $this->resizeImage($path, $size_id, 205, 205);
            }
            else if ($size_id == 'article')
            {
                $path = $this->resizeImage($path, $size_id, 380, 380);
            }
            else if ($size_id == 'enlarge')
            {
                $path = $this->resizeImage($path, $size_id, 1920, 1920);
            }
            else if ($size_id == 'original')
            {}
            else
            {
                throw $this->createNotFoundException('Invalid size: '.$size_id);
            }
        }
        return $path;
    }

    private function serveFile($path, $name = null, $download = false)
    {
        if (!file_exists($path)) throw new \Exception("File Doesn't exist: $path");

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

    public function tmpTypeAction($file, Request $request)
    {
        $path = $this->dir . 'tmp/' . $file;
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') == false) {
            throw new AccessDeniedException();
        }
        return $this->serveFile($path);
    }

    public function getEntityFromId($id, $repo, $given_path, $request)
    {
        /** @var $entity ResourceInterface */
        $entity = $this->getDoctrine()->getRepository($repo)->findOneById($id);

        if (is_null($entity)) throw $this->createNotFoundException("Instance $id doesn't exist within $repo");

        if ($given_path !== $entity->getUrlResourcePath())
        {
            return $this->redirect($this->generateUrl('IcsePublicBundle_resource', array_merge($request->query->all(), ['object' => $entity])), 301);
        }
        return $entity;
    }

    public function imagesTypeAction($given_path, Request $request)
    {
        $path_parts = explode('/', $given_path);
        $image_id = (count($path_parts) > 1) ? $path_parts[0] : explode('.', $given_path)[0];

        $image = $this->getEntityFromId($image_id, 'IcsePublicBundle:Image', $given_path, $request);
        if ($image instanceof Response) return $image;

        $path = $image->getFilePath();
        $path = $this->handleImageResizeRequest($path, $request);
        return $this->serveFile($path, $image->getDownloadName());
    }

    public function practicepartsTypeAction($given_path, Request $request)
    {
        if ($this->get('security.context')->isGranted('ROLE_USER') == false) throw new AccessDeniedException();

        $part_id = explode('/', $given_path)[0];

        $part = $this->getEntityFromId($part_id, 'IcseMembersBundle:PracticePart', $given_path, $request);
        if ($part instanceof Response) return $part;

        return $this->serveFile($part->getFilePath(), $part->getDownloadName());
    }

    public function legacypdfTypeAction($file, Request $request)
    {
        $path = $this->dir . 'legacypdf/' . $file;
        if ($this->get('security.context')->isGranted('ROLE_USER') == false) {
            throw new AccessDeniedException();
        }
        return $this->serveFile($path);
    }

    public function resourceAction($resource_type, $url_resource_path, Request $request)
    {
        $method_name = $resource_type.'TypeAction';
        if (!is_callable([$this, $method_name])) throw $this->createNotFoundException('Invalid Resource Type: '.$resource_type);
        return $this->$method_name($url_resource_path, $request);
    }
}
