<?php

namespace App\Repositories\Database\ChampionMastery;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;

class ChampionMasteryRepository
{
    /* @var LoggerInterface Logging Service */
    private $log;

    /* @var Connection Database Connection */
    private $dbConn;

    public function __construct(LoggerInterface $log, Connection $dbConn)
    {
        $this->log = $log;
        $this->dbConn = $dbConn;
    }

    public function store(array $masteries): bool
    {
        $update = ""
    }

    public function fetchBySummonerId(int $summonerId, string $region): array
    {
        $select = "SELECT * FROM champion_mastery WHERE summonerId = :summonerId AND region = :region";

        // TODO: Validate parameters
        $params = [
            'summonerId' => $summonerId,
            'region' => $region
        ];

        return $this->dbConn->fetchAll($select, $params);
    }
}
