<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Models\Champion\ChampionStats;
use LeagueOfData\Models\Champion\ChampionRegenResource;
use LeagueOfData\Models\Champion\ChampionAttack;
use LeagueOfData\Models\Champion\ChampionDefense;
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

    /**
     * Add a champion to the collection
     * 
     * @param Champion $champion
     */
    public function add(Champion $champion)
    {
        $this->champions[] = $champion;
    }

    /**
     * Find all Champion data by version
     *
     * @param string $version Version number
     * @return array Champion objects
     */
    public function findAll(string $version) : array
    {
        $request = new ChampionRequest(['version' => $version]);
        $response = $this->source->fetch($request);
        $this->champions = [];
        foreach ($response->data as $champion) {
            $this->champions[] = $this->create($champion, $response->version);
        }
        return $this->champions;
    }

    /**
     * Find a specific champion
     * 
     * @param int $championId
     * @param string $version
     * @return array Champion objects
     */
    public function find(int $championId, string $version) : array
    {
        $request = new ChampionRequest(['id' => $championId, 'region' => 'euw', 'version' => $version]);
        $response = $this->source->fetch($request);
        $this->champions = [ $this->create($response, $version) ];
        return $this->champions;
    }

    /**
     * Not implemented in JSON API calls
     */
    public function store()
    {
        $this->log->error("Request to store data through JSON API not available.");
    }

    /**
     * Create the champion object from array data
     * 
     * @param array $champion
     * @param string $version
     * @return Champion
     */
    private function create(array $champion, string $version) : Champion
    {
        $health = new ChampionRegenResource(
            ChampionRegenResource::RESOURCE_HEALTH,
            $champion->stats->hp,
            $champion->stats->hpperlevel,
            $champion->stats->hpregen,
            $champion->stats->hpregenperlevel
        );
        $resource = new ChampionRegenResource(
            ChampionRegenResource::RESOURCE_MANA,
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
                $champion->partype,
                $champion->tags,
                $stats,
                $version
            );
    }
}
