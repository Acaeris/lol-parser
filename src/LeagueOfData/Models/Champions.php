<?php

namespace LeagueOfData\Models;

interface Champions {
    function collectAll($version);
    function collect($id, $version);
}
