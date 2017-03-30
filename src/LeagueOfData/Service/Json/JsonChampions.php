<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Models\Champion\ChampionStats;
use LeagueOfData\Models\Champion\ChampionRegenResource;
use LeagueOfData\Models\Champion\ChampionAttack;
use LeagueOfData\Models\Champion\ChampionDefense;
use LeagueOfData\Service\Interfaces\ChampionServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use Psr\Log\LoggerInterface;

/**
 * Champion object JSON factory.
 * @package LeagueOfData\Service|Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonChampions implements ChampionServiceInterface
{
    /* @var AdapterInterface API adapter */
    private $source;
    /* @var LoggerInterface logger */
    private $log;
    /* @var array Champion Objects */
    private $champions;

    /**
     * Setup champion factory service
     *
     * @param AdapterInterface $adapter
     * @param LoggerInterface  $log
     */
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
        $this->champions[$champion->getID()] = $champion;
    }

    /**
     * Add all champion objects to internal array
     *
     * @param array $champions Champion objects
     */
    public function addAll(array $champions)
    {
        foreach ($champions as $champion) {
            $this->champions[$champion->getID()] = $champion;
        }
    }

    /**
     * Fetch Champions
     *
     * @param string $version
     * @param int    $championId
     *
     * @return array Champion Objects
     */
    public function fetch(string $version, int $championId = null) : array
    {
        $this->log->info("Fetching champions from API for version: {$version}".(
            isset($championId) ? " [{$championId}]" : ""
        ));

        $region = 'euw';
        $params = ['version' => $version, 'region' => $region];

        if (isset($championId) && !empty($championId)) {
            $params['id'] = $championId;
        }

        $request = new ChampionRequest($params);
        $response = $this->source->fetch($request);
        $this->champions = [];

        if ($response !== false) {
            if (!isset($response->data)) {
                $temp = new \stdClass();
                $temp->data = [ $response ];
                $response = $temp;
            }

            foreach ($response->data as $champion) {
                $this->champions[$champion->id] = $this->create($champion, $version);
            }
        }

        $this->log->info(count($this->champions)." champions fetched from API");

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
     * @param \stdClass $champion
     * @param string $version
     * @return Champion
     */
    private function create(\stdClass $champion, string $version) : Champion
    {
        $health = $this->createHealth($champion);
        $resource = $this->createResource($champion->partype, $champion);
        $attack = $this->createAttack($champion);
        $armor = $this->createDefense(ChampionDefense::DEFENSE_ARMOR, $champion);
        $magicResist = $this->createDefense(ChampionDefense::DEFENSE_MAGICRESIST, $champion);

        return new Champion(
            $champion->id,
            $champion->name,
            $champion->title,
            $champion->partype,
            $champion->tags,
            new ChampionStats($health, $resource, $attack, $armor, $magicResist, $champion->stats->movespeed),
            $version
        );
    }

    /**
     * Create Champion Health object
     * @param \stdClass $champion
     * @return ChampionRegenResource
     */
    private function createHealth(\stdClass $champion) : ChampionRegenResource
    {
        return new ChampionRegenResource(
            ChampionRegenResource::RESOURCE_HEALTH,
            $champion->stats->hp,
            $champion->stats->hpperlevel,
            $champion->stats->hpregen,
            $champion->stats->hpregenperlevel
        );
    }

    /**
     * Create Champion Resource object
     * @param string $type
     * @param \stdClass $champion
     * @return ChampionRegenResource
     */
    private function createResource(string $type, \stdClass $champion) : ChampionRegenResource
    {
        return new ChampionRegenResource(
            $type,
            $champion->stats->mp,
            $champion->stats->mpperlevel,
            $champion->stats->mpregen,
            $champion->stats->mpregenperlevel
        );
    }

    /**
     * Create Champion Defense object
     * @param string $type
     * @param \stdClass $champion
     * @return ChampionDefense
     */
    private function createDefense(string $type, \stdClass $champion) : ChampionDefense
    {
        return new ChampionDefense(
            $type,
            $champion->stats->armor,
            $champion->stats->armorperlevel
        );
    }

    /**
     * Create Champion Attack object
     * @param \stdClass $champion
     * @return ChampionAttack
     */
    private function createAttack(\stdClass $champion) : ChampionAttack
    {
        return new ChampionAttack(
            $champion->stats->attackrange,
            $champion->stats->attackdamage,
            $champion->stats->attackdamageperlevel,
            $champion->stats->attackspeedoffset,
            $champion->stats->attackspeedperlevel,
            $champion->stats->crit,
            $champion->stats->critperlevel
        );
    }
}
