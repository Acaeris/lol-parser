<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Adapters\Request\ChampionStatsRequest;
use LeagueOfData\Service\Interfaces\ChampionServiceInterface;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;
use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Models\Champion\ChampionStats;
use LeagueOfData\Models\Champion\ChampionRegenResource;
use LeagueOfData\Models\Champion\ChampionAttack;
use LeagueOfData\Models\Champion\ChampionDefense;
use LeagueOfData\Models\ResourceTrait;

/**
 * Champion object SQL factory.
 * @package LeagueOfData\Service\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class SqlChampions implements ChampionServiceInterface
{
    /* @var AdapterInterface DB adapter */
    private $dbAdapter;
    /* @var LoggerInterface Logger */
    private $log;
    /* @var JsonChampionStats Champion Stat factory */
    private $statBuilder;
    /* @var array Champion objects */
    private $champions = [];

    /**
     * Setup champion factory service
     *
     * @param AdapterInterface $adapter
     * @param LoggerInterface  $log
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log,
        ChampionStatsServiceInterface $statBuilder)
    {
        $this->dbAdapter = $adapter;
        $this->log = $log;
        $this->statBuilder = $statBuilder;
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
     * @param string $region
     *
     * @return array Champion Objects
     */
    public function fetch(string $version, int $championId = null, string $region = 'euw') : array
    {
        $this->log->info("Fetching champions from DB for version: {$version}".(
            isset($championId) ? " [{$championId}]" : ""
        ));

        $where = [ 'version' => $version, 'region' => $region ];

        if (isset($championId) && !empty($championId)) {
            $where['champion_id'] = $championId;
        }

        $request = new ChampionRequest($where, '*');
        $results = $this->dbAdapter->fetch($request);
        $this->champions = [];
        $this->processResults($results);
        $this->log->info(count($this->champions)." champions fetched from DB");

        return $this->champions;
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
                $this->convertChampionToArray($champion)
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
     * Get collection of champions for transfer to a different process.
     *
     * @return array Champion objects
     */
    public function transfer() : array
    {
        return $this->champions;
    }

    /**
     * Converts Champion object into SQL data array
     *
     * @param Champion $champion
     *
     * @return array
     */
    private function convertChampionToArray(Champion $champion) : array
    {
        return [
            'champion_id' => $champion->getID(),
            'champion_name' => $champion->name(),
            'title' => $champion->title(),
            'resource_type' => $champion->resourceType(),
            'tags' => $champion->tagsAsString(),
            'version' => $champion->version(),
            'region' => $champion->region()
        ];
    }

    /**
     * Converts the champions stats for SQL insertion
     *
     * @param ChampionStats $stats
     * @return array
     */
    private function convertStatsToArray(ChampionStats $stats) : array
    {
        return [
            'moveSpeed' => $stats->moveSpeed(),
            'hp' => $stats->health()->baseValue(),
            'hpPerLevel' => $stats->health()->increasePerLevel(),
            'hpRegen' => $stats->health()->regenBaseValue(),
            'hpRegenPerLevel' => $stats->health()->regenIncreasePerLevel(),
            'resource' => $stats->resource()->baseValue(),
            'resourcePerLevel' => $stats->resource()->increasePerLevel(),
            'resourceRegen' => $stats->resource()->regenBaseValue(),
            'resourceRegenPerLevel' => $stats->resource()->regenIncreasePerLevel(),
            'attackRange' => $stats->attack()->range(),
            'attackDamage' => $stats->attack()->baseDamage(),
            'attackDamagePerLevel' => $stats->attack()->damagePerLevel(),
            'attackSpeedOffset' => $stats->attack()->attackSpeed(),
            'attackSpeedPerLevel' => $stats->attack()->attackSpeedPerLevel(),
            'crit' => $stats->attack()->baseCritChance(),
            'critPerLevel' => $stats->attack()->critChancePerLevel(),
            'armor' => $stats->armor()->baseValue(),
            'armorPerLevel' => $stats->armor()->increasePerLevel(),
            'spellBlock' => $stats->magicResist()->baseValue(),
            'spellBlockPerLevel' => $stats->magicResist()->increasePerLevel()
        ];
    }

    /**
     * Store the champion stats in the database
     * @param Champion $champion
     */
    private function storeStats(Champion $champion)
    {
        $stats = $this->convertStatsToArray($champion->stats());

        foreach ($stats as $key => $value) {
            $request = new ChampionStatsRequest(
                ['champion_id' => $champion->getID(), 'version' => $champion->version(), 'stat_name' => $key],
                'champion_id',
                [
                    'champion_id' => $champion->getID(),
                    'stat_name' => $key,
                    'stat_value' => $value,
                    'version' => $champion->version(),
                    'region' => $champion->region()
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
     *
     * @return Champion
     */
    private function create(array $champion) : Champion
    {
        return new Champion(
            $champion['champion_id'],
            $champion['champion_name'],
            $champion['title'],
            $champion['resource_type'],
            explode('|', $champion['tags']),
            $this->statBuilder->fetch($champion['version'], $champion['champion_id'], $champion['region']),
            $champion['version'],
            $champion['region']
        );
    }

    /**
     * Convert result data into Champion objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            if (!is_array($results)) {
                $results = [ $results ];
            }

            foreach ($results as $champion) {
                $this->champions[$champion['champion_id']] = $this->create($champion);
            }
        }
    }
}
