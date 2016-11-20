<?php

namespace LeagueOfData\Service\Sql;

use LeagueOfData\Service\Interfaces\ChampionService;
use LeagueOfData\Models\Champion;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;

final class SqlChampions implements ChampionService
{
    private $db;
    private $log;
    private $champions;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->db = $adapter;
        $this->log = $log;
    }

    public function add(Champion $champion)
    {
        $this->champions[] = $champion;
    }

    public function store()
    {
        foreach ($this->champions as $champion) {
            if ($this->db->fetch('champion', [
                'query' => 'SELECT name FROM champion WHERE id = ? AND version = ?',
                'params' => [ $champion->id(), $champion->version() ]
            ])) {
                $this->db->update('champion', $champion->toArray(), [
                    'id' => $champion->id(),
                    'version' => $champion->version()
                ]);
            } else {
                $this->db->insert('champion', $champion->toArray());
            }
        }
    }

    public function findAll($version)
    {
        $this->champions = [];
        $results = $this->db->fetch('champion', [
            'query' => 'SELECT * FROM champion WHERE version = ?',
            'params' => [$version]
        ]);
        if ($results !== false) {
            foreach ($results as $champion) {
                $this->champions[] = $this->create($champion);
            }
        }
        return $this->champions;
    }

    public function find($id, $version)
    {
        $result = $this->db->fetch('champion', [
            'query' => 'SELECT * FROM champion WHERE id = ? AND version = ?',
            'params' => [$id, $version]
        ]);
        $this->champions = [ $this->create($result) ];
        return $this->champions;
    }

    public function transfer()
    {
        return $this->champions;
    }

    private function create($champion)
    {
        return new Champion(
            $champion['id'],
            $champion['name'],
            $champion['title'],
            [],
            $champion['version']
        );
    }
}
