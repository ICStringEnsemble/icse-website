<?php

namespace Icse\MembersBundle\Form\DataTransformer;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;


class NullableTimeToStringTransformer extends DateTimeToStringTransformer
{
    const MAGIC_NULL_TIME = '23-59-59';

    /**
     * Transforms a DateTime object into a date string with the configured format
     * and timezone
     */
    public function transform($value)
    {
        if (!is_null($value) && $value->format('H-i-s') == self::MAGIC_NULL_TIME)
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
            $result = \DateTime::createFromFormat('H-i-s|', self::MAGIC_NULL_TIME);
        }
        return $result;
    }
}
