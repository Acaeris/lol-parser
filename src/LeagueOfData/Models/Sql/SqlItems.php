<?php

namespace LeagueOfData\Models\Sql;

use LeagueOfData\Models\Interfaces\Items;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;

final class SqlItems implements Items
{
    private $db;
    private $log;
    private $items;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->db = $adapter;
        $this->log = $log;
    }

    public function store(AdapterInterface $adapter)
    {
        foreach ($this->items as $item) {
            $item->store($adapter);
        }
    }

    public function collectAll()
    {
        $this->items = [];
        $results = $this->db->fetchAll('SELECT * FROM items');
        foreach ($results as $item) {
            $this->items[] = new Item($item);
        }
        return $this->items;
    }

    public function collect($id)
    {
        $result = $this->db->fetchAssoc('SELECT * FROM items WHERE id = ?', array($id));
        $this->items = [ new Item($result) ];
        return $this->items;
    }

    public function transfer()
    {
        return $this->items;
    }
}
