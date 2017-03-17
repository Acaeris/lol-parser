<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Service\Interfaces\ChampionService;
use LeagueOfData\Models\Champion\Champion;

final class SqlChampions implements ChampionService
{
    private $dbAdapter;
    private $log;
    private $champions;

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
     * Store the champion objects in the database
     */
    public function store()
    {
        foreach ($this->champions as $champion) {
            $request = new ChampionRequest(['id' => $champion->getID(), 'version' => $champion->version()],
                'SELECT name FROM champion WHERE id = :id AND version = :version', $champion->toArray());

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
     * @param int $championId
     * @return array Champion Objects
     */
    public function fetch(string $version, int $championId = null) : array
    {
        $this->log->info("Fetching champions for version: {$version}" . (isset($championId) ? " [{$championId}]" : ""));

        if (isset($championId) && !empty($championId)) {
            return $this->find($championId, $version);
        }

        return $this->findAll($version);
    }

    /**
     * Find all Champion data
     *
     * @param string $version Version number
     * @return array Champion objects
     */
    public function findAll(string $version) : array
    {
        $this->champions = [];
        $request = new ChampionRequest(['version' => $version], 'SELECT * FROM champion WHERE version = :version');
        $results = $this->dbAdapter->fetch($request);
        if ($results !== false) {
            foreach ($results as $champion) {
                $this->champions[] = Champion::fromState($champion);
            }
        }
        return $this->champions;
    }

    /**
     * Find a specific champion
     * 
     * @param string $version
     * @param int $championId
     * @return array Champion objects
     */
    public function find(string $version, int $championId) : array
    {
        $request = new ChampionRequest(['id' => $championId, 'version' => $version],
            'SELECT * FROM champion WHERE id = :id AND version = :version');
        $result = $this->dbAdapter->fetch($request);
        $this->champions = [ Champion::fromState($result) ];
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
}
