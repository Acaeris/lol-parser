<?php

namespace spec\App\Mappers\Champions;

use PhpSpec\ObjectBehavior;

class ChampionBlockItemMapperSpec extends ObjectBehavior
{
    private $mockData = [
        "id" => 3112,
        "count" => 1
    ];

    public function it_can_map_array_data()
    {
        $this->mapFromArray($this->mockData)
            ->shouldReturnAnInstanceOf('App\Models\Champions\ChampionBlockItem');
    }
}
