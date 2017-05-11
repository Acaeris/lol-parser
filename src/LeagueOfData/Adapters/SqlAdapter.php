<?php

namespace LeagueOfData\Adapters;

use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;

/**
 * SQL Adapter class
 * @package LeagueOfData\Adapters
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class SqlAdapter implements AdapterInterface
{
    /* @var LoggerInterface Logger */
    private $log;
    /* @var Connection DB */
    private $database;

    /**
     * Set up the SQL adapter
     *
     * @param LoggerInterface $log
     * @param Connection      $database
     */
    public function __construct(LoggerInterface $log, Connection $database)
    {
        $this->log = $log;
        $this->database = $database;
    }

    /**
     * Insert object into SQL
     *
     * @param RequestInterface $request Request object
     *
     * @return int Insert response
     */
    public function insert(RequestInterface $request) : int
    {
        $request->requestFormat(Request::TYPE_SQL);

        return $this->database->insert($request->type(), $request->data());
    }

    /**
     * Fetch data from SQL
     *
     * @param RequestInterface $request Request object
     *
     * @return array Fetch response
     */
    public function fetch(RequestInterface $request) : array
    {
        $request->requestFormat(Request::TYPE_SQL);

        return $this->database->fetchAll($request->query(), $request->where());
    }

    /**
     * Update object in SQL
     *
     * @param RequestInterface $request Request object
     *
     * @return int Update response.
     */
    public function update(RequestInterface $request) : int
    {
        $request->requestFormat(Request::TYPE_SQL);

        return $this->database->update($request->type(), $request->data(), $request->where());
    }
}
