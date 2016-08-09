<?php

namespace LeagueOfData\Models;

interface MatchHistories {
    function add($id, $region);
    function findBySummoner($id);
}
