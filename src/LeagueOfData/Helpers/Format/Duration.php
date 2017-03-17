<?php

namespace LeagueOfData\Helpers\Format;

class Duration
{
    public function __construct()
    {
    }

    public function format($duration)
    {
        $mins = str_pad(intval($duration / 60), 2, '0', STR_PAD_LEFT);
        $seconds = str_pad($duration - $mins * 60, 2, '0', STR_PAD_LEFT);
        return $mins.":".$seconds;
    }
}
