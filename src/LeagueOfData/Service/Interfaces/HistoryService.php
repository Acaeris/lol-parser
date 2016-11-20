<?php

namespace LeagueOfData\Service\Interfaces;

interface HistoryService {
    function add($id, $timestamp, $type, $data);
    function findByRequest($params);
    function findByType($type);
}
