<?php

namespace Icse\MembersBundle\Controller\SuperAdmin;

use Symfony\Component\Form\FormBuilder;
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

    protected function buildForm(FormBuilder $form)
    {
        $form->add('member', 'entity', [
            'class' => 'IcseMembersBundle:Member',
            'choice_label' => 'full_name',
            'attr' => ['class' => 'entity-select'],
        ]);
        $form->add('position_name', 'icse_text_autosuggest');
        $form->add('start_year', 'integer');
        $form->add('sort_index', 'integer', ['label' => 'Sort index']);
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

    protected function prePersistEntity($entity)
    {
        $this->makeSortIndexAvailable($entity);
    }

}
