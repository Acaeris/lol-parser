<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
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
    /** @var AdapterInterface DB adapter */
    private $dbAdapter;
    /** @var LoggerInterface */
    private $log;
    /** @var array Champion objects */
    private $champions = [];

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->log = $log;
        $this->dbAdapter = $adapter;
    }

    /**
     * Factory to create Champion Stats objects from SQL
     *
     * @param array $champion
     *
     * @return ChampionStats
     */
    public function create(array $champion) : ChampionStats
    {
        return new ChampionStats(
            $champion['champion_id'],
            $this->createHealth($champion),
            $this->createResource($champion),
            $this->createAttack($champion),
            $this->createDefense(ChampionDefense::DEFENSE_ARMOR, $champion),
            $this->createDefense(ChampionDefense::DEFENSE_MAGICRESIST, $champion),
            isset($champion['moveSpeed']) ? $champion['moveSpeed'] : 0,
            $champion['version'],
            $champion['region']
        );
    }

    /**
     * Add a champion's stats to the collection
     *
     * @param ChampionStats $champion
     */
    public function add(ChampionStats $champion)
    {
        $this->champions[$champion->getID()] = $champion;
    }

    /**
     * Add all champion stats objects to internal array
     *
     * @param array $champions ChampionStats objects
     */
    public function addAll(array $champions)
    {
        foreach ($champions as $champion) {
            $this->champions[$champion->getID()] = $champion;
        }
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
        $this->log->debug("Fetching champion stats from DB for version: {$version}".(
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
        $this->log->debug(count($this->champions)." champions' stats fetched from DB");

        return $this->champions;
    }

    /**
     * Store the champion stats in the database
     */
    public function store()
    {
        $this->log->debug("Storing ".count($this->champions)." new/updated champions' stats");

        foreach ($this->champions as $champion) {
            $stats = $this->convertStatsToArray($champion);

            foreach ($stats as $key => $value) {
                $request = new ChampionStatsRequest(
                    ['champion_id' => $champion->getID(), 'version' => $champion->getVersion(), 'stat_name' => $key],
                    'champion_id',
                    [
                        'champion_id' => $champion->getID(),
                        'stat_name' => $key,
                        'stat_value' => $value,
                        'version' => $champion->getVersion(),
                        'region' => $champion->getRegion()
                    ]
                );

                if ($this->dbAdapter->fetch($request)) {
                    $this->dbAdapter->update($request);

                    continue;
                }
                $this->dbAdapter->insert($request);
            }
        }
    }

    /**
     * Get collection of champions' stats for transfer to a different process.
     *
     * @return array ChampionStats objects
     */
    public function transfer() : array
    {
        return $this->champions;
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
            isset($stats['attackRange']) ? $stats['attackRange'] : 0,
            isset($stats['attackDamage']) ? $stats['attackDamage'] : 0,
            isset($stats['attackDamagePerLevel']) ? $stats['attackDamagePerLevel'] : 0,
            isset($stats['attackSpeedOffset']) ? $stats['attackSpeedOffset'] : 0,
            isset($stats['attackSpeedPerLevel']) ? $stats['attackSpeedPerLevel'] : 0,
            isset($stats['crit']) ? $stats['crit'] : 0,
            isset($stats['critPerLevel']) ? $stats['critPerLevel'] : 0
        );
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
            'moveSpeed' => $stats->getMoveSpeed(),
            'hp' => $stats->getHealth()->getBaseValue(),
            'hpPerLevel' => $stats->getHealth()->getIncreasePerLevel(),
            'hpRegen' => $stats->getHealth()->getRegenBaseValue(),
            'hpRegenPerLevel' => $stats->getHealth()->regenIncreasePerLevel(),
            'resource' => $stats->getResource()->getBaseValue(),
            'resourcePerLevel' => $stats->getResource()->getIncreasePerLevel(),
            'resourceRegen' => $stats->getResource()->getRegenBaseValue(),
            'resourceRegenPerLevel' => $stats->getResource()->getRegenIncreasePerLevel(),
            'attackRange' => $stats->getAttack()->getRange(),
            'attackDamage' => $stats->getAttack()->getBaseDamage(),
            'attackDamagePerLevel' => $stats->getAttack()->getDamagePerLevel(),
            'attackSpeedOffset' => $stats->getAttack()->getAttackSpeed(),
            'attackSpeedPerLevel' => $stats->getAttack()->getAttackSpeedPerLevel(),
            'crit' => $stats->getAttack()->getBaseCritChance(),
            'critPerLevel' => $stats->getAttack()->getCritChancePerLevel(),
            'armor' => $stats->getArmor()->getBaseValue(),
            'armorPerLevel' => $stats->getArmor()->getIncreasePerLevel(),
            'spellBlock' => $stats->getMagicResist()->getBaseValue(),
            'spellBlockPerLevel' => $stats->getMagicResist()->getIncreasePerLevel()
        ];
    }
}
