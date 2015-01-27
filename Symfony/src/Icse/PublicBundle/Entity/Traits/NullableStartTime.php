<?php

namespace Icse\PublicBundle\Entity\Traits;

use Icse\MembersBundle\Form\DataTransformer\NullableTimeToStringTransformer;

trait NullableStartTime
{
    public function isStartTimeKnown()
    {
        $time = $this->getStartsAt();
        if (is_null($time)) return false;
        if ($time->format('H-i-s') == NullableTimeToStringTransformer::MAGIC_NULL_TIME) return false;
        return true;
    }
}