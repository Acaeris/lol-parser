<?php

namespace spec\LeagueOfData\Repository\Match;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\Match\MatchInterface;
use LeagueOfData\Entity\Match\MatchPlayerInterface;

class MatchRepositorySpec extends ObjectBehavior
{
    private $mockData = [
        'gameId' => 1,
        'seasonId' => 9,
        'mapId' => 11,
        'gameMode' => "ASSASSINATE",
        'gameType' => "MATCHED_GAME",
        'gameDuration' => 865,
        'region' => "euw",
        'gameVersion' => '7.12.190.9002',
        'participantIdentities' => [
            [
                "player" => [
                    "accountId" => 212390613
                ],
                "participantId" => 1
            ]
        ],
        "participants" => [
            [
                "participantId" => 1,
                "championId" => 120
            ]
        ]
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

    public function it_can_merge_player_data_for_processing()
    {
        $this->mergePlayerData($this->mockData)->shouldReturn([
            1 => [
                "participantId" => 1,
                "championId" => 120,
                "accountId" => 212390613
            ]
        ]);
    }

    public function it_can_build_player_objects()
    {
        $data = $this->mergePlayerData($this->mockData);
        $this->buildPlayerObjects($data)->shouldReturnArrayOfPlayers();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfMatches' => function ($matches) {
                foreach ($matches as $match) {
                    if (!$match instanceof MatchInterface) {
                        return false;
                    }
                }
                return true;
            },
            'returnArrayOfPlayers' => function ($players) {
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
