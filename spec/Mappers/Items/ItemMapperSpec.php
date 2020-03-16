<?php

namespace spec\App\Mappers\Items;

use App\Mappers\ImageMapper;
use App\Mappers\StatMapper;
use App\Models\Image;
use App\Models\Items\Item;
use App\Models\Stat;
use PhpSpec\ObjectBehavior;

class ItemMapperSpec extends ObjectBehavior
{
    private $mockData = [
        "item_id" => 1001,
        "item_name" => "Boots of Speed",
        "colloq" => "Boots1",
        "image" => [
            'full' => 'full.png',
            'group' => 'champion',
            'sprite' => 'sprite.png',
            'x' => 0,
            'y' => 0,
            'w' => 64,
            'h' => 64
        ],
        "description" => 'Test Description',
        "sanitized_description" => "Test Description",
        "tags" => ['boots'],
        "plain_text" => 'Test Description',
        "stats" => [
            [
                "stat_name" => "FlatMoveSpeedMod",
                "stat_value" => 30,
            ]
        ],
        "base_value" => 100,
        "total_value" => 100,
        "sell_value" => 75,
        "purchasable" => true,
        "effects" => ['Some effects'],
        "hide_from_all" => false,
        "in_store" => true,
        "consume_on_full" => false,
        "consumed" => false,
        "into" => [1002],
        "from" => [],
        "maps" => ['SR', 'ARAM', 'ARURF', 'URF'],
        "special_recipe" => 0,
        "required_champion" => '',
        "group" => 'Movement Speed',
        "depth" => 0,
        "stacks" => 0,
        "version" => "7.4.3",
        "region" => "euw"
    ];

    public function let(
        ImageMapper $imageMapper,
        Image $image,
        StatMapper $statMapper,
        Stat $stat
    ) {
        $imageMapper->mapFromArray($this->mockData['image'])->willReturn($image);
        $statMapper->mapFromArray($this->mockData['stats'][0])->willReturn($stat);
        $this->beConstructedWith($imageMapper, $statMapper);
    }

    public function it_can_map_from_an_array()
    {
        $this->mapFromArray($this->mockData)->shouldReturnAnInstanceOf(Item::class);
    }
}