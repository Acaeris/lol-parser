<?php

namespace LeagueOfData\Service\Interfaces;

interface SummonerService {
    function add($id, $name, $level, $iconId, $revisionDate);
    function find($ids);
}
