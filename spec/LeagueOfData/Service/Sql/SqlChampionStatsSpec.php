<?php

namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionStatsRequest;
use LeagueOfData\Models\Champion\ChampionStats;

class SqlChampionStatsSpec extends ObjectBehavior
{
    private $mockData = [
        [
            'champion_id' => 266,
            'stat_name' => 'Test',
            'stat_value' => 1,
            'version' => '7.9.1',
            'region' => 'euw'
        ]
    ];

    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $adapter->fetch(new ChampionStatsRequest(['version' => '7.9.1', 'region' => 'euw'], '*'))
            ->willReturn($this->mockData);
        $adapter->fetch(new ChampionStatsRequest(['champion_id' => 266, 'version' => '7.9.1', 'region' => 'euw'], '*'))
            ->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlChampionStats');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface');
    }

    public function it_should_fetch_all_if_only_version_passed()
    {
        $this->fetch('7.9.1')->shouldReturnArrayOfChampionStats();
    }

    public function it_should_fetch_one_if_version_and_id_passed()
    {
        $this->fetch('7.9.1', 266)->shouldReturnArrayOfChampionStats();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfChampionStats' => function($championStats) {
                foreach ($championStats as $stat) {
                    if (!$stat instanceof ChampionStats) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
