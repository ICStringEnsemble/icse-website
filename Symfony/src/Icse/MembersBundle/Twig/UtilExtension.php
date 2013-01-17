<?php

namespace Icse\MembersBundle\Twig;

class UtilExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'getCell' => new \Twig_Filter_Method($this, 'getCell'),
        );
    }

    public function getCell($entity, $column)
    {
        return call_user_func($column['cell'], $entity);
    }

    public function getName()
    {
        return 'util_extension';
    }
} 
