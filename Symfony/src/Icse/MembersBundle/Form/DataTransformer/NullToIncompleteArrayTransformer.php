<?php

namespace Icse\MembersBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;


class NullToIncompleteArrayTransformer implements  DataTransformerInterface
{
    public function transform($array)
    {
        return $array;
    }

    public function reverseTransform($array)
    {
        $any_are_null = false;
        foreach($array as $value){
            if ($value == '' or is_null($value)) $any_are_null = true;
            break;
        }
        return $any_are_null ? null : $array;
    }
}
