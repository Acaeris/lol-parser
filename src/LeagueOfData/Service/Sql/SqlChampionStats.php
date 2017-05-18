<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request\ChampionStatsRequest;
use LeagueOfData\Models\Champion\ChampionStats;
use LeagueOfData\Models\Champion\ChampionDefense;
use LeagueOfData\Models\Champion\ChampionRegenResource;
use LeagueOfData\Models\Champion\ChampionAttack;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;
use LeagueOfData\Models\Interfaces\ChampionDefenseInterface;
use LeagueOfData\Models\Interfaces\ChampionAttackInterface;
use LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface;

/**
 * Champion Stats object SQL factory
 *
 * @package LeagueOfData\Service\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlChampionStats implements ChampionStatsServiceInterface
{
    /**
     * @var AdapterInterface DB adapter
     */
    private $dbAdapter;
    /**
     * @var LoggerInterface
     */
    private $log;
    /**
     * @var array Champion objects
     */
    private $champions = [];

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->log = $log;
        $this->dbAdapter = $adapter;
    }

    /**
     * Add all champion stats objects to internal array
     *
     * @param array $champions ChampionStats objects
     */
    public function add(array $champions)
    {
        foreach ($champions as $champion) {
            if ($champion instanceof ChampionStatsInterface) {
                $this->champions[$champion->getChampionID()] = $champion;
                continue;
            }
            $this->log->error('Incorrect object supplied to Champion Stats service', [$champion]);
        }
    }

    /**
     * Factory to create Champion Stats objects from SQL
     *
     * @param array $champion
     * @return ChampionStats
     */
    public function create(array $champion) : ChampionStatsInterface
    {
        return new ChampionStats(
            $champion['champion_id'],
            $this->createResource('hp', $champion),
            $this->createResource('resource', $champion),
            $this->createAttack($champion),
            $this->createDefense(ChampionDefense::DEFENSE_ARMOR, $champion),
            $this->createDefense(ChampionDefense::DEFENSE_MAGICRESIST, $champion),
            isset($champion['moveSpeed']) ? $champion['moveSpeed'] : 0,
            $champion['version'],
            $champion['region']
        );
    }

    /**
     * Fetch Champions Stats
     *
     * @param RequestInterface $request
     * @return array ChampionStats Objects
     */
    public function fetch(RequestInterface $request) : array
    {
        $this->log->debug("Fetching champion stats from DB");
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
                    [
                        'champion_id' => $champion->getChampionID(),
                        'version' => $champion->getVersion(),
                        'stat_name' => $key
                    ],
                    'champion_id',
                    [
                        'champion_id' => $champion->getChampionID(),
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
                throw new \Exception('Stat Results in unexpected format');
            }
            
            $champions = [];

            foreach ($results as $stat) {
                $champions[$stat['champion_id']]['champion_id'] = $stat['champion_id'];
                $champions[$stat['champion_id']]['version'] = $stat['version'];
                $champions[$stat['champion_id']]['region'] = $stat['region'];
                $champions[$stat['champion_id']][$stat['stat_name']] = $stat['stat_value'];
            }

            foreach ($champions as $id => $champion) {
                $this->champions[$id] = $this->create($champion);
            }

        }
    }

    /**
     * Create Champion Resource object
     *
     * @param string $type
     * @param array $stats
     * @return ChampionRegenResourceInterface
     */
    private function createResource(string $type, array $stats) : ChampionRegenResourceInterface
    {
        return new ChampionRegenResource(
            isset($stats[$type]) ? $stats[$type] : 0,
            isset($stats[$type.'PerLevel']) ? $stats[$type.'PerLevel'] : 0,
            isset($stats[$type.'Regen']) ? $stats[$type.'Regen'] : 0,
            isset($stats[$type.'RegenPerLevel']) ? $stats[$type.'RegenPerLevel'] : 0
        );
    }

    /**
     * Create Champion Defense object
     * @param string $type
     * @param array $stats
     * @return ChampionDefenseInterface
     */
    private function createDefense(string $type, array $stats) : ChampionDefenseInterface
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
     * @return ChampionAttackInterface
     */
    private function createAttack(array $stats) : ChampionAttackInterface
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
     * @param ChampionStatsInterface $stats
     * @return array
     */
    private function convertStatsToArray(ChampionStatsInterface $stats) : array
    {
        return [
            'moveSpeed' => $stats->getMoveSpeed(),
            'hp' => $stats->getHealth()->getBaseValue(),
            'hpPerLevel' => $stats->getHealth()->getIncreasePerLevel(),
            'hpRegen' => $stats->getHealth()->getRegenBaseValue(),
            'hpRegenPerLevel' => $stats->getHealth()->getRegenIncreasePerLevel(),
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
