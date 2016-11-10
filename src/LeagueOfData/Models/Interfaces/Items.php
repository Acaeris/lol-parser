<?php

namespace LeagueOfData\Models\Interfaces;

interface Items {
    function collectAll($verison);
    function collect($id, $version);
}
