<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Models\Champion;
use LeagueOfData\Models\ChampionStats;
use LeagueOfData\Models\ChampionResource;
use LeagueOfData\Models\ChampionAttack;
use LeagueOfData\Models\ChampionDefense;
use LeagueOfData\Service\Interfaces\ChampionService;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use Psr\Log\LoggerInterface;

final class JsonChampions implements ChampionService
{
    private $source;
    private $log;
    private $champions;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->source = $adapter;
        $this->log = $log;
    }

    public function add(Champion $champion) {
        $this->champions[] = $champion;
    }

    public function findAll($version) {
        $request = new ChampionRequest(['version' => $version]);
        $response = $this->source->fetch($request);
        $this->champions = [];
        foreach ($response->data as $champion) {
            $this->champions[] = $this->create($champion, $response->version);
        }
        return $this->champions;
    }

    public function find($id, $version)
    {
        $request = new ChampionRequest(['id' => $id, 'region' => 'euw', 'version' => $version]);
        $response = $this->source->fetch($request);
        $this->champions = [ $this->create($response, $version) ];
        return $this->champions;
    }

    public function store() {
        $this->log->error("Request to store data through JSON API not available.");
    }

    private function create($champion, $version)
    {
        $health = new ChampionResource(
            ChampionResource::RESOURCE_HEALTH,
            $champion->stats->hp,
            $champion->stats->hpperlevel,
            $champion->stats->hpregen,
            $champion->stats->hpregenperlevel
        );
        $resource = new ChampionResource(
            $champion->partype,
            $champion->stats->mp,
            $champion->stats->mpperlevel,
            $champion->stats->mpregen,
            $champion->stats->mpregenperlevel
        );
        $attack = new ChampionAttack(
            $champion->stats->attackrange,
            $champion->stats->attackdamage,
            $champion->stats->attackdamageperlevel,
            $champion->stats->attackspeedoffset,
            $champion->stats->attackspeedperlevel,
            $champion->stats->crit,
            $champion->stats->critperlevel
        );
        $armor = new ChampionDefense(
            ChampionDefense::DEFENSE_ARMOR,
            $champion->stats->armor,
            $champion->stats->armorperlevel
        );
        $magicResist = new ChampionDefense(
            ChampionDefense::DEFENSE_MAGICRESIST,
            $champion->stats->spellblock,
            $champion->stats->spellblockperlevel
        );
        $stats = new ChampionStats($health, $resource, $attack, $armor, $magicResist, $champion->stats->movespeed);
        
        return new Champion(
                $champion->id,
                $champion->name,
                $champion->title,
                $stats,
                $version
            );
    }
}
