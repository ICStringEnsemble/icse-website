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
            'new_image_form' => $this->getCreationForm($this->newInstance())->createView()
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

    protected function buildCreationForm($form)
    {
        $form->add('file', 'file', [
            'property_path' => 'file_from_form',
            'constraints' => [new Constraints\NotNull],
        ]);
    }

    protected function buildEditForm($form)
    {
        $form->add('name', 'text');
        $form->add('in_slideshow', 'checkbox', ['required' => false]);
    }
}
