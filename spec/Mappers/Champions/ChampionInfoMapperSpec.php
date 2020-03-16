<?php

namespace spec\App\Mappers\Champions;

use PhpSpec\ObjectBehavior;

class ChampionInfoMapperSpec extends ObjectBehavior
{
    private $mockData = [
        "difficulty" => 1,
        "attack" => 2,
        "defense" => 3,
        "magic" => 4
    ];

    public function it_can_map_data_from_array()
    {
        $this->mapFromArray($this->mockData)
            ->shouldReturnAnInstanceOf('App\Models\Champions\ChampionInfo');
    }
}
