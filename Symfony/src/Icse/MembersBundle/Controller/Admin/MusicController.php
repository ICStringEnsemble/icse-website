<?php

namespace Icse\MembersBundle\Controller\Admin;

use Icse\MembersBundle\Entity\PracticePart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints;
use Doctrine\Common\Collections\ArrayCollection;

use Icse\MembersBundle\Form\Type\PracticePartType;
use Icse\PublicBundle\Entity\PieceOfMusic;
use Common\Tools; 

class MusicController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcsePublicBundle:PieceOfMusic');
    }

    protected function getViewName()
    {
        return 'IcseMembersBundle:Admin:music.html.twig';
    }

    protected function newInstance()
    {
        return new PieceOfMusic();
    }

    protected function newInstancePrototype()
    {
        $piece = $this->newInstance();
        $part = new PracticePart();
        $piece->addPrototypePracticePart($part);
        return $piece;
    }

    protected function getListContent()
    {
        $entities = $this->repository()->findBy(array(), array('composer'=>'asc'));

        $fields = [
            'Composer' => function(PieceOfMusic $x){return $x->getComposer();},
            'Name' => function(PieceOfMusic $x){return $x->getName();},
            'Parts' => function(PieceOfMusic $x){return count($x->getPracticeParts());},
            'Last updated' => function(PieceOfMusic $x){return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();},
        ];
        return ["fields" => $fields, "entities" => $entities];
    }

    protected function indexData()
    {
        return [
            'new_part_form' => $this->getNewPracticePartForm(new PracticePart)->createView()
        ];
    }

    private function addNewPracticePart(Request $request, $piece)
    {
        $part = new PracticePart();
        $part->setPiece($piece);
        $part->setSortIndex(0);

        $form = $this->getNewPracticePartForm($part);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($part);
            $em->flush();

            return $this->get('ajax_response_gen')->returnSuccess(['entity' => $part]);
        }
        else
        {
            return $this->get('ajax_response_gen')->returnFail($form);
        }
    }

    protected function instanceOperationAction($request, $id, $op)
    {
        if ($op === "newpracticepart")
        {
            /* @var $event Event */
            $piece = $this->getEntityById($id);
            return $this->addNewPracticePart($request, $piece);
        }
        return parent::instanceOperationAction($request, $id, $op);
    }

    protected function getFormCollectionNames()
    {
        return ['practice_parts'];
    }

    protected function getForm($entity)
    {
        $form = $this->createFormBuilder($entity, ['cascade_validation' => true])
            ->add('name', 'text')
            ->add('composer', 'text')
            ->add('practice_parts', 'collection', [
                'type' => new PracticePartType,
                'allow_delete' => true
            ]);
        return $form->getForm();
    }

    private function getNewPracticePartForm(PracticePart $part)
    {
        return $this->createFormBuilder($part)
            ->setAction($this->generateUrl('IcseMembersBundle_musicadmin', ['arg' => '__ID__', 'op' => 'newpracticepart']))
            ->add('file', 'file', [
                'property_path' => 'form_file_and_instrument',
                'constraints' => [new Constraints\NotNull],
            ])
            ->add('sort_index')
            ->getForm();
    }

    protected function putData($request, $entity)
    {
        /** @var $entity PieceOfMusic */
        $practice_parts_before = new ArrayCollection;
        foreach ($entity->getPracticeParts() as $part) $practice_parts_before->add($part);

        $form = $this->getForm($entity);
        $form->submit($request->request->get($form->getName()));

        $entity->setUpdatedAt(new \DateTime());
        $entity->setUpdatedBy($this->get('security.context')->getToken()->getUser());

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            foreach ($practice_parts_before as $old_part)
            {
                if ($entity->getPracticeParts()->contains($old_part) === false) $em->remove($old_part);
            }

            $em->persist($entity);
            $em->flush();
            return $this->get('ajax_response_gen')->returnSuccess(['entity' => $entity]);
        }
        else
        {
            // Cancel any changes
            if ($em->contains($entity))
            {
                $em->refresh($entity);
                foreach ($entity->getPracticeParts() as $part) $em->refresh($part);
            }
            return $this->get('ajax_response_gen')->returnFail($form);
        }  
    }



}
