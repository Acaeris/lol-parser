<?php

namespace LeagueOfData\Service\Interfaces;

interface HistoryService {
    public function add($id, $timestamp, $type, $data);
    public function findByRequest($params);
    public function findByType($type);
}
