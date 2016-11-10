<?php

namespace LeagueOfData\Models\Sql;

use LeagueOfData\Models\Interfaces\Histories;
use LeagueOfData\Adapters\SQL\HistoryRequest;

final class SqlHistories implements Histories
{
    private $source;

    public function __construct(AdapterInterface $adapter)
    {
        $this->source = $adapter;
    }

    public function add($timestamp, $type, $data)
    {
        $request = new HistoryRequest();
        $this->source->insert($request->prepare(['timestamp' => $timestamp, 'type' => $type, 'json' => $data]));
        return new SqlHistory($timestamp, $type, $data);
    }

    public function findByRequest($params) {
        
    }

    public function findByType($type) {
        
    }
}
