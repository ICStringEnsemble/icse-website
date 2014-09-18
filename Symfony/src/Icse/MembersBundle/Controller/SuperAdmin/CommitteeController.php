<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Icse\MembersBundle\Controller\Admin\EntityAdminController;
use Icse\MembersBundle\Entity\CommitteeRole;

class CommitteeController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcseMembersBundle:CommitteeRole');
    }

    protected function getViewName()
    {
        return 'IcseMembersBundle:SuperAdmin:committee.html.twig';
    }

    protected function newInstance()
    {
        return new CommitteeRole();
    }

    protected function getListContent()
    {
        $entities = $this->repository()->findBy(array(), array('start_year'=>'desc', 'sort_index'=>'asc'));

        $fields = [
            'Index' => function(CommitteeRole $x){return $x->getSortIndex();},
            'Year' => function(CommitteeRole $x){$y0=$x->getStartYear(); $y1=$y0+1; return "$y0&ndash;$y1";},
            'Role' => function(CommitteeRole $x){return $x->getPositionName();},
            'Name' => function(CommitteeRole $x){return $x->getMember()->getFullName();},
        ];
        return ["fields" => $fields, "entities" => $entities];
    }

    protected function getForm($entity)
    {
        $form = $this->createFormBuilder($entity)
            ->add('member', 'entity', [
                'class' => 'IcseMembersBundle:Member',
                'property' => 'full_name',
            ])
            ->add('position_name', 'text')
            ->add('start_year', 'integer')
            ->add('sort_index', 'integer', ['label' => 'Sort index'])
            ->getForm();
        return $form;
    }

    private function makeSortIndexAvailable(CommitteeRole $fixed_entity)
    {
        $entities_of_year = $this->getDoctrine()->getRepository('IcseMembersBundle:CommitteeRole')
                                                ->findBy(['start_year' => $fixed_entity->getStartYear()], ['sort_index' => 'ASC']);

        $reserved_i = $fixed_entity->getSortIndex();
        $next_assignment = $reserved_i + 1;

        foreach($entities_of_year as $e)
        {
            if ($e !== $fixed_entity and $e->getSortIndex() >= $reserved_i)
            {
                $e->setSortIndex($next_assignment);
                $next_assignment++;
            }
        }
    }

    protected function putData($request, $entity)
    {
        $form = $this->getForm($entity);
        $form->submit($request);

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid())
        {
            $this->makeSortIndexAvailable($entity);

            $em->persist($entity);
            $em->flush();
            return $this->get('ajax_response_gen')->returnSuccess();
        }
        else
        {
            // Cancel any changes
            if ($em->contains($entity))
            {
                $em->refresh($entity);
            }
            return $this->get('ajax_response_gen')->returnFail($form);
        }
    }

}
