<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Service\Interfaces\ChampionService;
use LeagueOfData\Models\Champion;
use LeagueOfData\Models\ChampionResource;
use LeagueOfData\Models\ChampionAttack;
use LeagueOfData\Models\ChampionDefense;

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
            $request = new ChampionRequest(['id' => $champion->id(), 'version' => $champion->version()],
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
                $this->champions[] = $this->create($champion);
            }
        }
        return $this->champions;
    }

    public function find($id, $version)
    {
        $request = new ChampionRequest(['id' => $id, 'version' => $version],
            'SELECT * FROM champion WHERE id = :id AND version = :version');
        $result = $this->db->fetch($request);
        $this->champions = [ $this->create($result) ];
        return $this->champions;
    }

    public function transfer()
    {
        return $this->champions;
    }

    private function create($champion)
    {
        $health = new ChampionResource(
            ChampionResource::RESOURCE_HEALTH,
            $champion['hp'],
            $champion['hpPerLevel'],
            $champion['hpRegen'],
            $champion['hpRegenPerLevel']
        );
        $resource = new ChampionResource(
            $champion['resourceType'],
            $champion['mp'],
            $champion['mpPerLevel'],
            $champion['mpRegen'],
            $champion['mpRegenPerLevel']
        );
        $attack = new ChampionAttack(
            $champion['attackRange'],
            $champion['attackDamage'],
            $champion['attackDamagePerLevel'],
            $champion['attackSpeedOffset'],
            $champion['attackSpeedPerLevel'],
            $champion['crit'],
            $champion['critPerLevel']
        );
        $armor = new ChampionDefense(
            ChampionDefense::DEFENSE_ARMOR,
            $champion['armor'],
            $champion['armorPerLevel']
        );
        $magicResist = new ChampionDefense(
            ChampionDefense::DEFENSE_MAGICRESIST,
            $champion['spellBlock'],
            $champion['spellBlockPerLevel']
        );
        $stats = new ChampionStats($health, $resource, $attack, $armor, $magicResist, $champion['moveSpeed']);

        return new Champion(
            $champion['id'],
            $champion['name'],
            $champion['title'],
            $stats,
            $champion['version']
        );
    }
}
