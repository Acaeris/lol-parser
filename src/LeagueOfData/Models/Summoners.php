<?php

namespace LeagueOfData\Models;

interface Summoners {
    function add($id, $name, $level, $iconId, $revisionDate);
    function find($ids);
}
