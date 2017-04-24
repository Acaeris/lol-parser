<?php

namespace LeagueOfData\Helpers\Format;

class GameMode
{
    private $modes = [
        'CLASSIC' => "Classic"
    ];

    public function format($mode)
    {
        if (isset($this->modes[$mode])) {
            return $this->modes[$mode];
        }
        return $mode;
    }
}
