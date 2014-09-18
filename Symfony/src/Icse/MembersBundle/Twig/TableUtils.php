<?php

namespace Icse\MembersBundle\Twig;

class TableUtils extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'getCell' => new \Twig_Filter_Method($this, 'getCell'),
        );
    }

    public function getCell($entity, $getter)
    {
        return call_user_func($getter, $entity);
    }

    public function yesOrNo($value)
    {
        return $value ? "Yes" : "No";
    }

    public function getName()
    {
        return 'icse.twig.table_utils';
    }
} 
