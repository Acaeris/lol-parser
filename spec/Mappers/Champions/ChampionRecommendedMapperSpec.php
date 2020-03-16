<?php

namespace spec\App\Mappers\Champions;

use PhpSpec\ObjectBehavior;
use App\Mappers\Champions\ChampionBlockMapper;
use App\Models\Champions\ChampionBlock;

class ChampionRecommendedMapperSpec extends ObjectBehavior
{
    private $mockData = [
        "champion" => "Annie",
        "title" => "Map12",
        "map" => "HA",
        "blocks" => [
            [
                "recMath" => false,
                "items" => [
                    [
                        "id" => 3112,
                        "count" => 1
                    ]
                ],
                "type" => "starting"
            ]
        ],
        "type" => "riot",
        "mode" => "ARAM"
    ];

    public function let(
        ChampionBlockMapper $blockMapper
    ) {
        $this->beConstructedWith($blockMapper);
    }

    public function it_can_map_array_data(
        ChampionBlockMapper $blockMapper,
        ChampionBlock $block
    ) {
        $blockMapper->mapFromArray($this->mockData['blocks'][0])
            ->willReturn($block);

        $this->mapFromArray($this->mockData)
            ->shouldReturnAnInstanceOf('App\Models\Champions\ChampionRecommended');
    }
}
