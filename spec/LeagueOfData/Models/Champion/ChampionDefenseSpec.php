<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;

class ChampionDefenseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('armor', 100, 1);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionDefense');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionDefenseInterface');
    }

    function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    function it_is_a_resource()
    {
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ResourceInterface');
    }

    function it_can_output_a_type_of_defense()
    {
        $this->type()->shouldReturn('armor');
    }

    function it_can_output_a_base_value()
    {
        $this->baseValue()->shouldReturn(100);
    }

    function it_can_output_a_value_per_level()
    {
        $this->increasePerLevel()->shouldReturn(1);
    }

    function it_can_output_the_value_at_a_given_level()
    {
        $this->valueAtLevel(5)->shouldReturn(105);
    }

    function it_can_be_converted_to_an_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'armor' => 100,
            'armorPerLevel' => 1
        ]);
    }
}
