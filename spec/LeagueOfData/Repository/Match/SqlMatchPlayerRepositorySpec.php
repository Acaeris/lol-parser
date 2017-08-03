<?php

namespace spec\LeagueOfData\Repository\Match;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use LeagueOfData\Entity\Match\MatchPlayerInterface;

class SqlMatchPlayerRepositorySpec extends ObjectBehavior
{
    private $mockData = [
        [
            "match_id" => 1,
            "account_id" => 2,
            "champion_id" => 3,
            "region" => "euw"
        ]
    ];

    public function let(
        Connection $dbConn,
        LoggerInterface $logger
    ) {
        $dbConn->fetchAll(new AnyValuesToken)->willReturn($this->mockData);
        $this->beConstructedWith($dbConn, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Match\SqlMatchPlayerRepository');
        $this->shouldImplement('LeagueOfData\Repository\StoreRepositoryInterface');
    }

    public function it_should_fetch_players_for_a_match()
    {
        $this->fetch("")->shouldReturnArrayOfMatchPlayers();
    }

    public function it_can_convert_data_to_player_object()
    {
        $this->create($this->mockData[0])->shouldImplement('LeagueOfData\Entity\Match\MatchPlayerInterface');
    }

    public function it_can_add_and_retrieve_player_objects_from_collection(MatchPlayerInterface $player) {
        $player->getAccountID()->willReturn(1);
        $this->add([$player]);
        $this->transfer()->shouldReturnArrayOfMatchPlayers();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfMatchPlayers' => function(array $players) {
                foreach ($players as $player) {
                    if (!$player instanceof MatchPlayerInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
