<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Service\Interfaces\ChampionService;
use LeagueOfData\Models\Champion;

final class SqlChampions implements ChampionService
{
    private $database;
    private $log;
    private $champions;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->database = $adapter;
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

            if ($this->database->fetch($request)) {
                $this->database->update($request);
            } else {
                $this->database->insert($request);
            }
        }
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
        $results = $this->database->fetch($request);
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
     * @param int $championId
     * @param string $version
     * @return array Champion objects
     */
    public function find(int $championId, string $version) : array
    {
        $request = new ChampionRequest(['id' => $championId, 'version' => $version],
            'SELECT * FROM champion WHERE id = :id AND version = :version');
        $result = $this->database->fetch($request);
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
