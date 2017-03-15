<?php

namespace LeagueOfData\Adapters;

use Psr\Log\LoggerInterface;

class SqlAdapter implements AdapterInterface
{
    /* @var Psr\Log\LoggerInterface Logger */
    private $log;
    /* @var object DB */
    private $database;

    public function __construct(LoggerInterface $log, $database)
    {
        $this->log = $log;
        $this->database = $database;
    }

    /**
     * Insert object into SQL
     *
     * @param LeagueOfData\Adapters\RequestInterface Request object
     */
    public function insert(RequestInterface $request)
    {
        $request->requestFormat(RequestInterface::REQUEST_SQL);
        return $this->database->insert($request->type(), $request->data());
    }

    /**
     * Fetch data from SQL
     *
     * @param LeagueOfData\Adapters\RequestInterface Request object
     */
    public function fetch(RequestInterface $request)
    {
        $request->requestFormat(RequestInterface::REQUEST_SQL);
        return $this->database->fetchAll($request->query(), $request->where());
    }

    /**
     * Update object in SQL
     *
     * @param LeagueOfData\Adapters\RequestInterface Request object
     */
    public function update(RequestInterface $request)
    {
        $request->requestFormat(RequestInterface::REQUEST_SQL);
        return $this->database->update($request->type(), $request->data(), $request->where());
    }
}
