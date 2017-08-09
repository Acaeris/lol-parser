<?php

namespace LeagueOfData\Repository\Summoner;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Repository\StoreRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Summoner\SummonerInterface;
use LeagueOfData\Entity\Summoner\Summoner;

class SqlSummonerRepository implements StoreRepositoryInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Connection
     */
    private $dbConn;

    /**
     * @var array Summoners
     */
    private $summoners = [];

    public function __construct(Connection $dbConn, LoggerInterface $logger)
    {
        $this->dbConn = $dbConn;
        $this->logger = $logger;
    }

    /**
     * Add objects to internal array
     *
     * @param array $summoners
     */
    public function add(array $summoners)
    {
        foreach ($summoners as $summoner) {
            if ($summoner instanceof SummonerInterface) {
                $this->summoners[$summoner->getSummonerID()] = $summoner;
                continue;
            }
            $this->logger->error('Incorrect object supplied to Summoner repository', [$summoner]);
        }
    }

    /**
     * Clear the internal collection
     */
    public function clear()
    {
        $this->summoners = [];
    }

    /**
     * Factory for creating objects
     *
     * @param  array $summoner
     * @return EntityInterface
     */
    public function create(array $summoner): EntityInterface
    {
        return new Summoner(
            $summoner['summoner_id'],
            $summoner['account_id'],
            $summoner['summoner_name'],
            $summoner['summoner_level'],
            $summoner['profile_icon'],
            $summoner['revision_date'],
            $summoner['region']
        );
    }

    /**
     * Fetch Champions
     *
     * @param  string $query
     * @param  array  $where
     * @return array Summoner objects
     */
    public function fetch(string $query, array $where = []): array
    {
        $this->logger->debug("Fetching summoners from DB");
        $results = $this->dbConn->fetchAll($query, $where);
        $this->summoners = [];
        $this->processResults($results);
        $this->logger->debug(count($this->summoners)." summoners fetched from DB");

        return $this->summoners;
    }

    /**
     * Store the summoner objects in the database
     */
    public function store()
    {
        $this->logger->debug("Storing ".count($this->summoners)." new/updated summoners");

        $select = "SELECT summoner_id FROM summoners WHERE summoner_id = :summoner_id AND region = :region";

        foreach ($this->summoners as $summoner) {
            if ($this->dbConn->fetchAll($select, $summoner->getKeyData())) {
                $this->dbConn->update('summoners', $this->convertSummonerToArray($summoner));
                continue;
            }
            $this->dbConn->insert('summoners', $this->convertSummonerToArray($summoner));
        }
    }

    /**
     * Transfer object out to another repository
     *
     * @return array
     */
    public function transfer(): array
    {
        return $this->summoners;
    }

    /**
     * Convert result data into Summoner objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            foreach ($results as $summoner) {
                $this->summoners[$summoner['summoner_id']] = $this->create($summoner);
            }
        }
    }

    /**
     * Converts Summoner object into SQL data array
     *
     * @param  SummonerInterface $summoner
     * @return array
     */
    private function convertSummonerToArray(SummonerInterface $summoner): array
    {
        return [
            'summoner_id' => $summoner->getSummonerID(),
            'account_id' => $summoner->getAccountID(),
            'summoner_name' => $summoner->getName(),
            'summoner_level' => $summoner->getLevel(),
            'profile_icon' => $summoner->getProfileIconID(),
            'revision_date' => $summoner->getRevisionDate()->format('Y-m-d H:i:s'),
            'region' => $summoner->getRegion()
        ];
    }
}
