<?php

namespace LeagueOfData\Models\Interfaces;

interface Champions {
    function collectAll($version);
    function collect($id, $version);
}
