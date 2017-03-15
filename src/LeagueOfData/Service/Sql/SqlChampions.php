<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Service\Interfaces\ChampionService;
use LeagueOfData\Models\Champion;

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
            $request = new ChampionRequest(['id' => $champion->getID(), 'version' => $champion->getVersion()],
                'SELECT name FROM champion WHERE id = :id AND version = :version', $champion->toArray());

            if ($this->db->fetch($request)) {
                $this->db->update($request);
            } else {
                $this->db->insert($request);
            }
        }
    }

    public function findAll($version)
    {
        $this->champions = [];
        $request = new ChampionRequest(['version' => $version], 'SELECT * FROM champion WHERE version = :version');
        $results = $this->db->fetch($request);
        if ($results !== false) {
            foreach ($results as $champion) {
                $this->champions[] = Champion::fromState($champion);
            }
        }
        return $this->champions;
    }

    public function find($id, $version)
    {
        $request = new ChampionRequest(['id' => $id, 'version' => $version],
            'SELECT * FROM champion WHERE id = :id AND version = :version');
        $result = $this->db->fetch($request);
        $this->champions = [ Champion::fromState($result) ];
        return $this->champions;
    }

    public function transfer()
    {
        return $this->champions;
    }
}
