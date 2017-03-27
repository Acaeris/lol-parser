<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Service\Interfaces\ChampionServiceInterface;
use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Models\Champion\ChampionStats;
use LeagueOfData\Models\Champion\ChampionRegenResource;
use LeagueOfData\Models\Champion\ChampionAttack;
use LeagueOfData\Models\Champion\ChampionDefense;

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
        $this->champions[] = $champion;
    }

    /**
     * Add all champion objects to internal array
     *
     * @param array $champions Champion objects
     */
    public function addAll(array $champions)
    {
        $this->champions = array_merge($this->champions, $champions);
    }

    /**
     * Store the champion objects in the database
     */
    public function store()
    {
        foreach ($this->champions as $champion) {
            $request = new ChampionRequest(
                ['id' => $champion->getID(), 'version' => $champion->version()],
                'SELECT name FROM champion WHERE id = :id AND version = :version',
                $champion->toArray()
            );

            if ($this->dbAdapter->fetch($request)) {
                $this->dbAdapter->update($request);

                return;
            }
            $this->dbAdapter->insert($request);
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
        $this->log->info("Fetching champions from DB for version: {$version}".(isset($championId) ? " [{$championId}]" : ""));

        $request = new ChampionRequest(
            [ 'version' => $version ],
            'SELECT * FROM champion WHERE version = :version'
        );

        if (isset($championId) && !empty($championId)) {
            $request = new ChampionRequest(
                [ 'id' => $championId, 'version' => $version ],
                'SELECT * FROM champion WHERE id = :id AND version = :version'
            );
        }

        $this->champions = [];
        $results = $this->dbAdapter->fetch($request);

        if ($results !== false) {
            if (!is_array($results)) {
                $results = [ $results ];
            }

            foreach ($results as $champion) {
                $this->champions[] = $this->create($champion);
            }
        }

        return $this->champions;
    }

    /**
     * Get collection of champions for transfer to a different process
     *
     * @return array Champion objects
     */
    public function transfer() : array
    {
        return $this->champions;
    }

    public function create(array $champion) : Champion
    {
        $health = new ChampionRegenResource(
            ChampionRegenResource::RESOURCE_HEALTH,
            $champion[ChampionRegenResource::RESOURCE_HEALTH],
            $champion[ChampionRegenResource::RESOURCE_HEALTH . 'PerLevel'],
            $champion[ChampionRegenResource::RESOURCE_HEALTH . 'Regen'],
            $champion[ChampionRegenResource::RESOURCE_HEALTH . 'RegenPerLevel']
        );
        $resource = new ChampionRegenResource(
            ChampionRegenResource::RESOURCE_MANA,
            $champion[ChampionRegenResource::RESOURCE_MANA],
            $champion[ChampionRegenResource::RESOURCE_MANA . 'PerLevel'],
            $champion[ChampionRegenResource::RESOURCE_MANA . 'Regen'],
            $champion[ChampionRegenResource::RESOURCE_MANA . 'RegenPerLevel']
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
            $champion[ChampionDefense::DEFENSE_ARMOR],
            $champion[ChampionDefense::DEFENSE_ARMOR.'PerLevel']
        );
        $magicResist = new ChampionDefense(
            ChampionDefense::DEFENSE_MAGICRESIST,
            $champion[ChampionDefense::DEFENSE_MAGICRESIST],
            $champion[ChampionDefense::DEFENSE_MAGICRESIST.'PerLevel']
        );

        $stats = new ChampionStats($health, $resource, $attack, $armor, $magicResist, $champion['moveSpeed']);
        
        return new Champion(
            $champion['id'],
            $champion['name'],
            $champion['title'],
            $champion['resourceType'],
            explode('|', $champion['tags']),
            $stats,
            $champion['version']
        );
    }
}
