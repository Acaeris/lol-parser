<?php

namespace spec\App\Repositories\Database\ChampionMastery;

use App\Models\ChampionMastery\ChampionMastery;
use Doctrine\DBAL\Connection;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

class ChampionMasteryRepositorySpec extends ObjectBehavior
{
    public function let(
        LoggerInterface $log,
        Connection $dbConn
    ) {
        $this->beConstructedWith($log, $dbConn);
    }

    public function it_can_fetch_data_by_summoner_id(
        Connection $dbConn
    ) {
        $selectQuery = 'SELECT * FROM champion_mastery WHERE summonerId = :summonerId AND region = :region';

        $dbConn->fetchAll($selectQuery, ['summonerId' => 1, 'region' => 'euw'])->willReturn([]);

        $this->fetchBySummonerId(1, 'euw')->shouldReturn([]);
    }

    public function it_can_store_champion_mastery_data(
        ChampionMastery $mastery
    ) {
        $this->store([$mastery])->shouldReturn(true);
    }
}