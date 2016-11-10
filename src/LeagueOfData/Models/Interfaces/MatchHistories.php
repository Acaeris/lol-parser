<?php

namespace LeagueOfData\Models\Interfaces;

interface MatchHistories {
    function add($id, $region);
    function findBySummoner($id);
}
