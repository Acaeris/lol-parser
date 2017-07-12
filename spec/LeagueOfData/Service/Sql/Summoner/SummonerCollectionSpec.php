<?php
namespace spec\LeagueOfData\Service\Sql\Summoner;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Entity\Summoner\SummonerInterface;

class SummonerCollectionSpec extends ObjectBehavior
{
    private $mockData = [
        [
            'summoner_id' => 1,
            'account_id' => 2,
            'summoner_name' => "Acaeris",
            'summoner_level' => 30,
            'profile_icon' => 779,
            'revision_date' => '2017-07-01 17:53:38',
            'region' => 'euw'
        ]
    ];

    public function let(Connection $dbConn, LoggerInterface $logger)
    {
        $dbConn->fetchAll(new AnyValuesToken)->willReturn($this->mockData);
        $this->beConstructedWith($dbConn, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\Summoner\SummonerCollection');
        $this->shouldImplement('LeagueOfData\Service\StoreServiceInterface');
    }

    public function it_can_add_and_retrieve_summoner_objects_from_collection(SummonerInterface $summoner)
    {
        $summoner->getSummonerID()->willReturn(1);
        $this->add([$summoner]);
        $this->transfer()->shouldReturnArrayOfSummoners();
    }

    public function it_can_convert_data_to_champion_object()
    {
        $this->create($this->mockData[0])->shouldImplement('LeagueOfData\Entity\Summoner\SummonerInterface');
    }

    public function it_should_fetch_summoners()
    {
        $this->fetch("")->shouldReturnArrayOfSummoners();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfSummoners' => function(array $summoners) {
                foreach ($summoners as $summoner) {
                    if (!$summoner instanceof SummonerInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
