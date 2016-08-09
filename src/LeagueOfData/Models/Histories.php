<?php

namespace LeagueOfData\Models;

interface Histories {
    function add($id, $timestamp, $type, $data);
    function findByRequest($params);
    function findByType($type);
}
