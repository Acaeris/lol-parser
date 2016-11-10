<?php

namespace LeagueOfData\Models\Interfaces;

interface Histories {
    function add($id, $timestamp, $type, $data);
    function findByRequest($params);
    function findByType($type);
}
