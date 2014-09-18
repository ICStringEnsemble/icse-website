<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints;
use Icse\MembersBundle\Form\Type\FileInfoType;
use Icse\PublicBundle\Entity\Image;


class ImageController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcsePublicBundle:Image');
    }

    protected function getViewName()
    {
        return 'IcseMembersBundle:Admin:images.html.twig';
    }

    protected function getListViewName()
    {
        return 'IcseMembersBundle:Admin/entity_instance_list:images.html.twig';
    }

    protected function newInstance()
    {
        return new Image();
    }

    protected function indexData()
    {
        return [
            'new_image_form' => $this->getAddNewImageForm($this->newInstance())->createView()
        ];
    }

    protected function getListContent()
    {
        $entities = $this->repository()->findBy([], ['id'=>'desc']);

        $fields = [
            'ID' => function(Image $x){return $x->getId();},
            'Name' => function(Image $x){return $x->getName();},
            'Last updated' => function(Image $x){return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();},
        ];
        return ["fields" => $fields, "entities" => $entities];
    }

    protected function getAddNewImageForm($entity)
    {
        return $this->createFormBuilder($entity)
            ->setMethod('POST')
            ->add('file', 'file', [
                'property_path' => 'file_from_form',
                'constraints' => [new Constraints\NotNull],
            ])
            ->getForm();
    }

    protected function getForm($entity)
    {
        return $this->createFormBuilder($entity)
            ->setMethod('PUT')
            ->add('name', 'text')
            ->getForm();
    }

    protected function putData($request, $entity)
    {
        /** @var $entity Image */
        $is_creation = is_null($entity->getId());

        $form = $is_creation ? $this->getAddNewImageForm($entity) : $this->getForm($entity);

        $form->handleRequest($request);

        $entity->setUpdatedAt(new \DateTime());
        $entity->setUpdatedBy($this->get('security.context')->getToken()->getUser());

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            $em->persist($entity);
            $em->flush();
            return $this->get('ajax_response_gen')->returnSuccess(['entity' => $entity]);
        }
        else
        {
            if ($em->contains($entity))
            {
                $em->refresh($entity);
            }
            return $this->get('ajax_response_gen')->returnFail($form);
        }
    }

}
