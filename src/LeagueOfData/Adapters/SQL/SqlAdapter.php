<?php

namespace LeagueOfData\Adapters\SQL;

use LeagueOfData\Adapters\AdapterInterface;
use Psr\Log\LoggerInterface;

class SqlAdapter implements AdapterInterface {

    private $log;
    private $db;

    public function __construct(LoggerInterface $log, $db) {
        $this->log = $log;
        $this->db = $db;
    }

    public function insert($table, $data) {
        return $this->db->insert($table, $data);
    }

    public function fetch($type, $data) {
        return $this->db->fetchAll($data['query'], $data['params']);
    }

    public function update($table, $data, $where) {
        return $this->db->update($table, $data, $where);
    }
}
