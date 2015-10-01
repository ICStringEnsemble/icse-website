<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form;
use Symfony\Component\Validator\Constraints;

use Icse\MembersBundle\Entity\PracticePart;
use Icse\PublicBundle\Entity\PieceOfMusic;
use Icse\MembersBundle\Form\Type\PracticePartType;

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
            'Title' => function(PieceOfMusic $x){return $x->getName();},
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

    private function addNewPracticePart(Request $request, PieceOfMusic $piece)
    {
        $part = new PracticePart();

        $form = $this->getNewPracticePartForm($part);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $piece->addPracticePart($part);
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

    protected function buildForm(FormBuilder $form)
    {
        $form->add('composer', 'text');
        $form->add('name', 'text', ['label' => 'Title']);
        $form->add('practice_parts', 'collection', [
            'type' => new PracticePartType,
            'allow_delete' => true
        ]);
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

}
