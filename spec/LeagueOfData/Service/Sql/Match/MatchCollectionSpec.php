<?php

namespace spec\LeagueOfData\Service\Sql\Match;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use LeagueOfData\Entity\Match\MatchInterface;

class MatchCollectionSpec extends ObjectBehavior
{
    private $mockData = [
        [
            'match_id' => 1,
            'region' => "euw"
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
        $this->shouldHaveType('LeagueOfData\Service\Sql\Match\MatchCollection');
        $this->shouldImplement('LeagueOfData\Service\StoreServiceInterface');
    }

    public function it_should_fetch_a_match_list()
    {
        $this->fetch("")->shouldReturnArrayOfMatches();
    }

    public function it_can_add_and_retrieve_match_objects_from_collection(MatchInterface $match)
    {
        $match->getMatchID()->willReturn(1);
        $this->add([$match]);
        $this->transfer()->shouldReturnArrayOfMatches();
    }

    public function it_can_convert_data_to_match_object()
    {
        $this->create($this->mockData[0])->shouldImplement('LeagueOfData\Entity\Match\MatchInterface');
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfMatches' => function(array $matches) {
                foreach ($matches as $match) {
                    if (!$match instanceof MatchInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
