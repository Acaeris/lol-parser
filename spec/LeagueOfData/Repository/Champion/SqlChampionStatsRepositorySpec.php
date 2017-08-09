<?php

namespace spec\LeagueOfData\Repository\Champion;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Entity\Champion\ChampionStatsInterface;

class SqlChampionStatsRepositorySpec extends ObjectBehavior
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

    public function let(Connection $dbConn, LoggerInterface $logger)
    {
        $dbConn->fetchAll('', [])->willReturn($this->mockData);
        $this->beConstructedWith($dbConn, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Champion\SqlChampionStatsRepository');
        $this->shouldImplement('LeagueOfData\Repository\StoreRepositoryInterface');
    }

    public function it_should_fetch_champion_stats()
    {
        $this->fetch("")->shouldReturnArrayOfChampionStats();
    }

    public function it_can_convert_data_to_stat_object()
    {
        $this->create($this->mockData[0])->shouldImplement('LeagueOfData\Entity\Champion\ChampionStatsInterface');
    }

    public function it_can_add_and_retrieve_stat_objects_from_collection(ChampionStatsInterface $stats)
    {
        $stats->getChampionID()->willReturn(1);
        $this->add([$stats]);
        $this->transfer()->shouldReturnArrayOfChampionStats();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfChampionStats' => function (array $championStats) {
                foreach ($championStats as $stat) {
                    if (!$stat instanceof ChampionStatsInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
