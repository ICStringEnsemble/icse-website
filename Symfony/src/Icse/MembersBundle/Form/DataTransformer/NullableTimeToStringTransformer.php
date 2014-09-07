<?php

namespace Icse\MembersBundle\Form\DataTransformer;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;


class NullableTimeToStringTransformer extends DateTimeToStringTransformer
{
    /**
     * Transforms a DateTime object into a date string with the configured format
     * and timezone
     */
    public function transform($value)
    {
        if (!is_null($value) && $value->format('H-i-s') == '00-00-01')
        {
            $value = null;
        }
        return parent::transform($value);
    }

    /**
     * Transforms a date string in the configured timezone into a DateTime object.
     */
    public function reverseTransform($value)
    {
        $result = parent::reverseTransform($value);
        if (is_null($result))
        {
            $result = \DateTime::createFromFormat('H-i-s|', '00-00-01');
        }
        return $result;
    }
}
