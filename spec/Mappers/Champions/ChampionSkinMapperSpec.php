<?php

namespace spec\App\Mappers\Champions;

use PhpSpec\ObjectBehavior;

class ChampionSkinMapperSpec extends ObjectBehavior
{
    private $mockData = [
        "id" => 1000,
        "num" => 0,
        "name" => "default"
    ];

    public function it_can_map_from_array()
    {
        $this->mapFromArray($this->mockData)
            ->shouldReturnAnInstanceOf('App\Models\Champions\ChampionSkin');
    }
}
