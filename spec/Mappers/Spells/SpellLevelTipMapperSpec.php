<?php

namespace spec\App\Mappers\Spells;

use PhpSpec\ObjectBehavior;

class SpellLevelTipMapperSpec extends ObjectBehavior
{
    private $mockData = [
        "effect" => ["{{ e1 }} -> {{ e1NL }}", " {{ cost }} -> {{ costNL }}"],
        "label" => ["Damage", "Mana Cost"]
    ];

    public function it_can_map_array_data()
    {
        $this->mapFromArray($this->mockData)
            ->shouldReturnAnInstanceOf('App\Models\Spells\SpellLevelTip');
    }
}
