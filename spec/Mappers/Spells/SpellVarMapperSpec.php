<?php

namespace spec\App\Mappers\Spells;

use App\Models\Spells\SpellVar;
use PhpSpec\ObjectBehavior;

class SpellVarMapperSpec extends ObjectBehavior
{
    private $mockData = [
        "coeff" => [0.8],
        "link" => "spelldamage",
        "key" => "a1"
    ];

    public function it_can_map_array_data()
    {
        $this->mapFromArray($this->mockData)
            ->shouldReturnAnInstanceOf(SpellVar::class);
    }
}
