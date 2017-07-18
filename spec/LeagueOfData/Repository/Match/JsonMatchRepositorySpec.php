<?php

namespace spec\LeagueOfData\Repository\Match;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\Match\MatchInterface;

class MatchRepositorySpec extends ObjectBehavior
{
    private $mockData = [
        'gameId' => 1,
        'gameMode' => "ASSASSINATE",
        'region' => "euw",
        'gameVersion' => '7.12.190.9002'
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
        $this->shouldHaveType('LeagueOfData\Repository\Match\JsonMatchRepository');
        $this->shouldImplement('LeagueOfData\Repository\FetchRepositoryInterface');
    }

    public function it_can_convert_data_to_match_object()
    {
        $this->create($this->mockData)
            ->shouldImplement('LeagueOfData\Entity\Match\MatchInterface');
    }

    public function it_should_fetch_match_by_id(AdapterInterface $adapter)
    {
        $adapter->setOptions('match/v3/matches/1', new AnyValuesToken)->willReturn($adapter);
        $this->fetch(['match_id' => 1])->shouldReturnArrayOfMatches();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfMatches' => function($matches) {
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
