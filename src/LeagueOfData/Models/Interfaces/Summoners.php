<?php

namespace LeagueOfData\Models\Interfaces;

interface Summoners {
    function add($id, $name, $level, $iconId, $revisionDate);
    function find($ids);
}
