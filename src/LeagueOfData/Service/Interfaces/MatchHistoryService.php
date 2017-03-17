<?php

namespace LeagueOfData\Service\Interfaces;

interface MatchHistoryService
{
    public function add($id, $region);
    public function find($id);
}
