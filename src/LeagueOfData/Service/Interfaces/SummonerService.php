<?php

namespace LeagueOfData\Service\Interfaces;

interface SummonerService
{
    public function add($id, $name, $level, $iconId, $revisionDate);
    public function find($ids);
}
