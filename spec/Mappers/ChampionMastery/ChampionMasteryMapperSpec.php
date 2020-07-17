<?php

namespace spec\App\Mappers\ChampionMastery;

use App\Models\ChampionMastery\ChampionMastery;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;

class ChampionMasteryMapperSpec extends ObjectBehavior
{
    private $mockApiData = [
        [
            "championLevel" => 5,
            "chestGranted" => false,
            "championPoints" => 66937,
            "championPointsSinceLastLevel" => 45337,
            "championPointsUntilNextLevel" => 0,
            "summonerId" => "OHKSHr7Ubt8nqJ_0kgx5bRCB4YzQXYrQKJEZ6ACvsLlucec",
            "tokensEarned" => 1,
            "championId" => 103,
            "lastPlayTime" => 1572305756000
        ]
    ];

    public function it_can_map_data_from_api_response(
        ResponseInterface $response
    ) {
        $response->getBody()->willReturn($this->mockApiData);

        $this->mapFromApiData($response)->shouldReturnArrayOfChampionMasteries();
    }

    public function getMatchers(): array
    {
        return [
            'returnArrayOfChampionMasteries' => function(array $masteries) {
                foreach ($masteries as $mastery) {
                    if (!$mastery instanceof ChampionMastery) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}