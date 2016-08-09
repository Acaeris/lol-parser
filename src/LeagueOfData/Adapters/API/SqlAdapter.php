<?php

namespace LeagueOfData\Adapters\API;

use Psr\Log\LoggerInterface;

class SqlAdapter implements AdapterInterface {

    private $log;
    private $db;

    public function __construct($db, LoggerInterface $log) {
        $this->log = $log;
        $this->db = $db;
    }

    
}
