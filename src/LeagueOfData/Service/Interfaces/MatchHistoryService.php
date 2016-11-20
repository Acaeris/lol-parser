<?php

namespace LeagueOfData\Service\Interfaces;

interface MatchHistoryService {
    function add($id, $region);
    function find($id);
}
