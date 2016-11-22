<?php

namespace LeagueOfData\Adapters;

use Psr\Log\LoggerInterface;

class SqlAdapter implements AdapterInterface
{
    private $log;
    private $db;

    public function __construct(LoggerInterface $log, $db)
    {
        $this->log = $log;
        $this->db = $db;
    }

    public function insert(RequestInterface $request)
    {
        $request->outputFormat(RequestInterface::REQUEST_SQL);
        return $this->db->insert($request->type(), $request->data());
    }

    public function fetch(RequestInterface $request)
    {
        $request->outputFormat(RequestInterface::REQUEST_SQL);
        return $this->db->fetchAll($request->query(), $request->where());
    }

    public function update(RequestInterface $request)
    {
        $request->outputFormat(RequestInterface::REQUEST_SQL);
        return $this->db->update($request->type(), $request->data(), $request->where());
    }
}
