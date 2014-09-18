<?php

namespace Icse\PublicBundle\Controller;

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
        if ($request->query->has('size'))
        {
            $resizer = $this->get('icse.image_resizer');
            $path = $resizer->resize($path, $request->query->get('size'));
        }

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
        $path = 'Symfony/uploads/legacypdf/' . $file;
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
