<?php

namespace Icse\PublicBundle\Entity\Traits;


trait NullableStartTime
{
    public function isStartTimeKnown()
    {
        $time = $this->getStartsAt();
        if (is_null($time)) return false;
        if ($time->format('H-i-s') == '00-00-01') return false;
        return true;
    }
}