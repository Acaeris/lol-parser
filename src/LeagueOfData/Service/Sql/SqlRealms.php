<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Adapters\Request;
use LeagueOfData\Service\Interfaces\RealmServiceInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request\RealmRequest;
use LeagueOfData\Models\Realm;
use LeagueOfData\Models\Interfaces\RealmInterface;

/**
 * Realm object SQL factory.
 *
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlRealms implements RealmServiceInterface
{
    /**
     * @var Connection DB connection
     */
    private $dbConn;
    /**
     * @var LoggerInterface Logger
     */
    private $log;
    /**
     * @var array Realm objects
     */
    private $realms = [];

    /**
     * Setup Realm factory service
     *
     * @param Connection      $connection
     * @param LoggerInterface $logger
     */
    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->dbConn = $connection;
        $this->log = $logger;
    }

    /**
     * Add realm objects to internal array
     *
     * @param array $realms Realm objects
     */
    public function add(array $realms)
    {
        foreach ($realms as $realm) {
            if ($realm instanceof RealmInterface) {
                $this->realms[$realm->getVersion()] = $realm;
                continue;
            }
            $this->log->error('Incorrect object supplied to Realm service', [$realm]);
        }
    }

    /**
     * Factory to create realm objects
     *
     * @param array $realm
     * @return RealmInterface
     */
    public function create(array $realm) : RealmInterface
    {
        return new Realm($realm['cdn'], $realm['version']);
    }

    /**
     * Find all Realm data
     *
     * @param RequestInterface $request
     * @return array Realm objects
     */
    public function fetch(RequestInterface $request) : array
    {
        $this->realms = [];
        $request->requestFormat(Request::TYPE_SQL);
        $response = $this->dbConn->fetchAll($request->query(), $request->where());
        $this->processResults($response);

        return $this->realms;
    }

    /**
     * Store the version objects in the DB
     */
    public function store()
    {
        foreach ($this->realms as $realm) {
            $select = 'SELECT version FROM realms WHERE version = :version';
            $where = [ 'version' => $realm->getVersion() ];

            if ($this->dbConn->fetchAll($select, $where)) {
                $this->dbConn->update('realms', $this->convertRealmToArray($realm), $where);
                continue;
            }

            $this->dbConn->insert('realms', $this->convertRealmToArray($realm));
        }
    }

    /**
     * Get collection of realms for transfer to a different process
     *
     * @return array
     */
    public function transfer() : array
    {
        return $this->realms;
    }

    /**
     * Convert result data into Realm objects
     *
     * @param array $results
     * @throws \Exception
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            if (!is_array($results)) {
                throw new \Exception('Realms Results in unexpected format.');
            }

            foreach ($results as $realm) {
                $this->realms[$realm['version']] = $this->create($realm);
            }
        }
    }

    /**
     * Converts Realm object into SQL data array
     *
     * @param Realm $realm
     * @return array
     */
    private function convertRealmToArray(Realm $realm) : array
    {
        return [
            'version' => $realm->getVersion(),
            'cdn' => $this->getSourceUrl()
        ];
    }
}
