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
    /* @var AdapterInterface API adapter */
    private $source;
    /* @var LoggerInterface logger */
    private $log;
    /* @var array Champion Objects */
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
     * Fetch Champions
     *
     * @param string $version
     * @param int    $championId
     * @return array Champion Objects
     */
    public function fetch(string $version, int $championId = null) : array
    {
        $this->log->info("Fetching champions for version: {$version}".(isset($championId) ? " [{$championId}]" : ""));

        if (isset($championId) && !empty($championId)) {
            return $this->find($version, $championId);
        }

        return $this->findAll($version);
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
     * @param string $version
     * @param int    $championId
     * @return array Champion objects
     */
    public function find(string $version, int $championId) : array
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
     * Collection of Champion objects
     *
     * @return array
     */
    public function transfer() : array
    {
        return $this->champions;
    }

    /**
     * Create the champion object from array data
     *
     * @param array $champion
     * @param string $version
     * @return Champion
     */
    private function create(\stdClass $champion, string $version) : Champion
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
