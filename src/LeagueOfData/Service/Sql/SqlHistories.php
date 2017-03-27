<?php

namespace LeagueOfData\Service\Sql;

use LeagueOfData\Service\Interfaces\HistoryService;
use LeagueOfData\Adapters\SQL\HistoryRequest;
use LeagueOfData\Models\History;
use Psr\Log\LoggerInterface;

/**
 * Summoner History object SQL factory.
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class SqlHistories implements HistoryService
{
    /* @var LeagueOfData\Adapters\AdapterInterface DB adapter */
    private $dbAdapter;
    /* @var Psr\Log\LoggerInterface Logger */
    private $log;
    /* @var array History objects */
    private $entries = [];

    /**
     * Setup version factory service
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
     * Add an entry to the collection
     *
     * @param History $entry
     */
    public function add(History $entry)
    {
        $this->entries[] = $entry;
    }

    /**
     * Add all entries to internal array
     *
     * @param array $entries History objects
     */
    public function addAll(array $entries)
    {
        $this->entries = array_merge($this->entries, $entries);
    }

    /**
     * Find a specific Summoner History entry
     *
     * @param int $historyId
     * @return array Item objects
     */
    public function find(int $historyId): array
    {
        $this->entries = [];
        $request = new HistoryRequest(
            ['id' => $historyId],
            'SELECT * FROM history WHERE id = :id'
        );
        $results = $this->dbAdapter->fetch($request);

        if ($results !== false) {
            foreach ($results as $entry) {
                $this->entries[] = new History($entry);
            }
        }

        return $this->entries;
    }

    /**
     * Find all Summoner History data
     *
     * @param int $summonerId Summoner ID
     *
     * @return array History objects
     */
    public function findAll(int $summonerId): array
    {
        $this->entries = [];
        $request = new HistoryRequest(
            ['summonerId' => $summonerId],
            'SELECT * FROM history WHERE summonerId = :summonerId'
        );
        $results = $this->dbAdapter->fetch($request);

        if ($results !== false) {
            foreach ($results as $entry) {
                $this->entries[] = new History($entry);
            }
        }

        return $this->entries;
    }

    /**
     * Store the Summoner History objects in the database
     */
    public function store()
    {
        foreach ($this->entries as $entry) {
            $request = new HistoryRequest(
                [ 'id' => $entry->getID() ],
                'SELECT id FROM history WHERE id = :id',
                $entry->toArray()
            );

            if ($this->dbAdapter->fetch($request)) {
                $this->dbAdapter->update($request);
                continue;
            }
            
            $this->dbAdapter->insert($request);
        }
    }
}
