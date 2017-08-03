<?php

namespace spec\LeagueOfData\Repository\MatchList;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\Match\MatchPlayerInterface;

class JsonMatchListRepositorySpec extends ObjectBehavior
{
    private $mockData = [
        'gameId' => 1,
        'champion' => 103,
        'account_id' => 2,
        'region' => "euw"
    ];

    public function let(
        AdapterInterface $adapter,
        LoggerInterface $logger
    ) {
        $adapter->fetch()->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\MatchList\JsonMatchListRepository');
        $this->shouldImplement('LeagueOfData\Repository\FetchRepositoryInterface');
    }

    public function it_can_convert_data_to_match_object()
    {
        $this->create($this->mockData)
            ->shouldImplement('LeagueOfData\Entity\Match\MatchPlayerInterface');
    }

    public function it_can_fetch_recent_matchlist_by_account_id(AdapterInterface $adapter)
    {
        $adapter->setOptions('match/v3/matchlists/by-account/1/recent', new AnyValuesToken)->willReturn($adapter);
        $this->fetch(['account_id' => 1])->shouldReturnArrayOfMatchPlayers();
    }

    public function it_can_fetch_entire_matchlist_by_account_id(AdapterInterface $adapter)
    {
        $adapter->setOptions('match/v3/matchlists/by-account/1', new AnyValuesToken)->willReturn($adapter);
        $this->fetch(['account_id' => 1, 'all' => true])->shouldReturnArrayOfMatchPlayers();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfMatchPlayers' => function($matches) {
                foreach ($matches as $match) {
                    if (!$match instanceof MatchPlayerInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
