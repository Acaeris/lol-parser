<?php

namespace LeagueOfData\Helpers\Format;

class GameMode
{
    private $_modes = [
        'CLASSIC' => "Classic"
    ];

    public function format($mode)
    {
        if (isset($this->_modes[$mode])) {
            return $this->_modes[$mode];
        }
        return $mode;
    }
}
