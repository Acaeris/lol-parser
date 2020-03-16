<?php

namespace spec\App\Mappers\Champions;

use PhpSpec\ObjectBehavior;
use App\Mappers\Champions\ChampionBlockItemMapper;
use App\Models\Champions\ChampionBlockItem;

class ChampionBlockMapperSpec extends ObjectBehavior
{
    private $mockData = [
        "recMath" => false,
        "items" => [
            [
                "id" => 3112,
                "count" => 1
            ]
        ],
        "type" => "starting"
    ];

    public function let(
        ChampionBlockItemMapper $blockItemMapper
    ) {
        $this->beConstructedWith($blockItemMapper);
    }

    public function it_can_map_array_data(
        ChampionBlockItemMapper $blockItemMapper,
        ChampionBlockItem $blockItem
    ) {
        $blockItemMapper->mapFromArray($this->mockData['items'][0])
            ->willReturn($blockItem);

        $this->mapFromArray($this->mockData)
            ->shouldReturnAnInstanceOf('App\Models\Champions\ChampionBlock');
    }
}
