<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Adapters\Request\ChampionStatsRequest;
use LeagueOfData\Service\Interfaces\ChampionServiceInterface;
use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Models\Champion\ChampionStats;
use LeagueOfData\Models\Champion\ChampionRegenResource;
use LeagueOfData\Models\Champion\ChampionAttack;
use LeagueOfData\Models\Champion\ChampionDefense;
use LeagueOfData\Models\ResourceTrait;

/**
 * Champion object SQL factory.
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class SqlChampions implements ChampionServiceInterface
{
    /* @var LeagueOfData\Adapters\AdapterInterface DB adapter */
    private $dbAdapter;
    /* @var Psr\Log\LoggerInterface Logger */
    private $log;
    /* @var array Champion objects */
    private $champions = [];

    /**
     * Setup champion factory service
     *
     * @param AdapterInterface $adapter
     * @param LoggerInterface  $log
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->dbAdapter = $adapter;
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
     * Store the champion objects in the database
     */
    public function store()
    {
        $this->log->info("Storing ".count($this->champions)." new/updated champions");

        foreach ($this->champions as $champion) {
            $request = new ChampionRequest(
                ['champion_id' => $champion->getID(), 'version' => $champion->version()],
                'champion_name',
                $this->toSqlArray($champion)
            );

            $this->storeStats($champion);

            if ($this->dbAdapter->fetch($request)) {
                $this->dbAdapter->update($request);

                return;
            }
            $this->dbAdapter->insert($request);
        }
    }

    /**
     * Converts Champion object into SQL data array
     *
     * @param Champion $champion
     * @return array
     */
    private function toSqlArray(Champion $champion) : array
    {
        return [
            'champion_id' => $champion->getID(),
            'champion_name' => $champion->name(),
            'title' => $champion->title(),
            'resource_type' => $champion->resourceType(),
            'tags' => $champion->tagsAsString(),
            'version' => $champion->version()
        ];
    }

    /**
     * Get collection of champions for transfer to a different process.
     *
     * @return array Champion objects
     */
    public function transfer() : array
    {
        return $this->champions;
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
        $this->log->info("Fetching champions from DB for version: {$version}".(
            isset($championId) ? " [{$championId}]" : ""
        ));

        $where = [ 'version' => $version ];

        if (isset($championId) && !empty($championId)) {
            $where['champion_id'] = $championId;
        }

        $request = new ChampionRequest($where, '*');
        $this->champions = [];
        $results = $this->dbAdapter->fetch($request);

        if ($results !== false) {
            if (!is_array($results)) {
                $results = [ $results ];
            }

            foreach ($results as $champion) {
                $this->champions[$champion['champion_id']] = $this->create(
                    $champion,
                    $this->fetchStats($champion['champion_id'], $champion['version'])
                );
            }
        }

        return $this->champions;
    }

    /**
     * Fetch the stats for the given champion
     * @param int $championId
     * @param string $version
     * @return array
     */
    private function fetchStats(int $championId, string $version) : array
    {
        $request = new ChampionStatsRequest(
            ['champion_id' => $championId, 'version' => $version],
            '*'
        );
        $stats = [];
        $results = $this->dbAdapter->fetch($request);

        if ($results !== false) {
            foreach ($results as $stat) {
                $stats[$stat['stat_name']] = $stat['stat_value'];
            }
        }

        return $stats;
    }

    /**
     * Store the champion stats in the database
     * @param Champion $champion
     */
    private function storeStats(Champion $champion)
    {
        $stats = $champion->stats()->toArray();

        foreach ($stats as $key => $value) {
            $request = new ChampionStatsRequest(
                ['champion_id' => $champion->getID(), 'version' => $champion->version(), 'stat_name' => $key],
                'champion_id',
                [
                    'champion_id' => $champion->getID(),
                    'stat_name' => $key,
                    'stat_value' => $value,
                    'version' => $champion->version(),
                ]
            );

            if ($this->dbAdapter->fetch($request)) {
                $this->dbAdapter->update($request);

                return;
            }
            $this->dbAdapter->insert($request);
        }
    }

    /**
     * Build the Champion object from the given data.
     *
     * @param array $champion
     * @param array $stats
     * @return Champion
     */
    private function create(array $champion, array $stats) : Champion
    {
        $health = $this->createResource(ChampionRegenResource::RESOURCE_HEALTH, $stats);
        $resource = $this->createResource($champion['resource_type'], $stats);
        $attack = $this->createAttack($stats);
        $armor = $this->createDefense(ChampionDefense::DEFENSE_ARMOR, $stats);
        $magicResist = $this->createDefense(ChampionDefense::DEFENSE_MAGICRESIST, $stats);

        return new Champion(
            $champion['champion_id'],
            $champion['champion_name'],
            $champion['title'],
            $champion['resource_type'],
            explode('|', $champion['tags']),
            new ChampionStats($health, $resource, $attack, $armor, $magicResist, $stats['moveSpeed']),
            $champion['version']
        );
    }

    /**
     * Create Champion Resource object
     * @param string $type
     * @param array $stats
     * @return ChampionRegenResource
     */
    private function createResource(string $type, array $stats) : ChampionRegenResource
    {
        $code = ResourceTrait::$validResources[strtolower($type)];

        return new ChampionRegenResource(
            $code,
            isset($stats[$code]) ? $stats[$code] : 0,
            isset($stats[$code.'PerLevel']) ? $stats[$code.'PerLevel'] : 0,
            isset($stats[$code.'Regen']) ? $stats[$code.'Regen'] : 0,
            isset($stats[$code.'RegenPerLevel']) ? $stats[$code.'RegenPerLevel'] : 0
        );
    }

    /**
     * Create Champion Defense object
     * @param string $type
     * @param array $stats
     * @return ChampionDefense
     */
    private function createDefense(string $type, array $stats) : ChampionDefense
    {
        return new ChampionDefense(
            $type,
            isset($stats[$type]) ? $stats[$type] : 0,
            isset($stats[$type.'PerLevel']) ? $stats[$type.'PerLevel'] : 0
        );
    }

    /**
     * Create Champion Attack object
     * @param array $stats
     * @return ChampionAttack
     */
    private function createAttack(array $stats) : ChampionAttack
    {
        return new ChampionAttack(
            $stats['attackRange'],
            $stats['attackDamage'],
            $stats['attackDamagePerLevel'],
            $stats['attackSpeedOffset'],
            $stats['attackSpeedPerLevel'],
            $stats['crit'],
            $stats['critPerLevel']
        );
    }
}
