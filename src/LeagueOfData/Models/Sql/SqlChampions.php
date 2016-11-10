<?php

namespace LeagueOfData\Models\Sql;

use LeagueOfData\Models\Interfaces\Champions;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;

final class SqlChampions implements Champions
{
    private $db;
    private $log;
    private $champions;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->db = $adapter;
        $this->log = $log;
    }

    public function store(AdapterInterface $adapter)
    {
        foreach ($this->champions as $champion) {
            $champion->store($adapter);
        }
    }

    public function collectAll()
    {
        $this->champions = [];
        $results = $this->db->fetchAll('SELECT * FROM champions');
        foreach ($results as $champion) {
            $this->champions[] = new Champion($champion);
        }
        return $this->champions;
    }

    public function collect($id)
    {
        $result = $this->db->fetchAssoc('SELECT * FROM champions WHERE id = ?', array($id));
        $this->champions = [ new Champion($result) ];
        return $this->champions;
    }

    public function transfer()
    {
        return $this->champions;
    }
}
