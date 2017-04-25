<?php

namespace LeagueOfData\Service\Sql;

use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;
use LeagueOfData\Adapters\Request\ChampionStatsRequest;
use LeagueOfData\Models\Champion\ChampionStats;
use LeagueOfData\Models\Champion\ChampionDefense;
use LeagueOfData\Models\Champion\ChampionRegenResource;
use LeagueOfData\Models\Champion\ChampionAttack;

/**
 * Champion Stats object SQL factory
 *
 * @package LeagueOfData\Service\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlChampionStats implements ChampionStatsServiceInterface
{
    /* @var AdapterInterface DB adapter */
    private $dbAdapter;
    /* @var array Champion objects */
    private $champions = [];

    /**
     * @var LoggerInterface
     */
    private $log;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->log = $log;
        $this->dbAdapter = $adapter;
    }

    public function create(array $champion) : ChampionStats
    {
        $health = $this->createHealth($champion);
        $resource = $this->createResource($champion);
        $attack = $this->createAttack($champion);
        $armor = $this->createDefense(ChampionDefense::DEFENSE_ARMOR, $champion);
        $magicResist = $this->createDefense(ChampionDefense::DEFENSE_MAGICRESIST, $champion);
        
        return new ChampionStats(
            $champion['champion_id'],
            $health,
            $resource,
            $attack,
            $armor,
            $magicResist,
            $champion['moveSpeed'],
            $champion['version'],
            $champion['region']
        );
    }

    /**
     * Fetch Champions Stats
     *
     * @param string $version
     * @param int    $championId
     * @param string $region
     *
     * @return array ChampionStats Objects
     */
    public function fetch(string $version, int $championId = null, string $region = "euw") : array
    {
        $this->log->info("Fetching champion stats from DB for version: {$version}".(
            isset($championId) ? " [{$championId}]" : ""
        ));

        $where = [ 'version' => $version, 'region' => $region ];

        if (isset($championId) && !empty($championId)) {
            $where['champion_id'] = $championId;
        }

        $request = new ChampionStatsRequest($where, '*');
        $results = $this->dbAdapter->fetch($request);
        $this->champions = [];
        $this->processResults($results);
        $this->log->info(count($this->champions)." champions' stats fetched from DB");

        return $this->champions;
    }

    public function store()
    {
        $this->log->info("Storing ".count($this->champions)." new/updated champions' stats");

        foreach ($this->champions as $id => $champion) {
            $request = new ChampionStatsRequest(
                ['champion_id' => $champion->getID(), ]
            );
        }
    }

    /**
     * Convert result data into ChampionStats objects
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

    /**
     * Create Champion Health object
     *
     * @param array $stats
     *
     * @return ChampionRegenResource
     */
    private function createHealth(array $stats) : ChampionRegenResource
    {
        return new ChampionRegenResource(
            isset($stats['hp']) ? $stats['hp'] : 0,
            isset($stats['hpPerLevel']) ? $stats['hpPerLevel'] : 0,
            isset($stats['hpRegen']) ? $stats['hpRegen'] : 0,
            isset($stats['hpRegenPerLevel']) ? $stats['hpRegenPerLevel'] : 0
        );
    }

    /**
     * Create Champion Resource object
     *
     * @param array $stats
     *
     * @return ChampionRegenResource
     */
    private function createResource(array $stats) : ChampionRegenResource
    {
        return new ChampionRegenResource(
            isset($stats['resource']) ? $stats['resource'] : 0,
            isset($stats['resourcePerLevel']) ? $stats['resourcePerLevel'] : 0,
            isset($stats['resourceRegen']) ? $stats['resourceRegen'] : 0,
            isset($stats['resourceRegenPerLevel']) ? $stats['resourceRegenPerLevel'] : 0
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
