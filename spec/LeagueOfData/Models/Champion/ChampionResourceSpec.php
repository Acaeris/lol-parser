<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;

class ChampionResourceSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('hp', 100, 5, 10, 1);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionResource');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionResourceInterface');
    }

    function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    function it_is_a_resource()
    {
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ResourceInterface');
    }

    function it_can_output_a_type_of_resource()
    {
        $this->type()->shouldReturn("hp");
    }

    function it_can_output_a_base_value()
    {
        $this->baseValue()->shouldReturn(100);
    }

    function it_can_output_a_value_per_level()
    {
        $this->increasePerLevel()->shouldReturn(5);
    }

    function it_can_output_the_value_at_a_given_level()
    {
        $this->valueAtLevel(5)->shouldReturn(125);
    }

    function it_can_output_a_regen_base_value()
    {
        $this->regenBaseValue()->shouldReturn(10);
    }

    function it_can_output_a_regen_per_level_value()
    {
        $this->regenIncreasePerLevel()->shouldReturn(1);
    }

    function it_can_output_a_regen_value_at_a_given_level()
    {
        $this->regenAtLevel(5)->shouldReturn(15);
    }

    function it_can_output_the_data_as_an_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'hp' => 100,
            'hpPerLevel' => 5,
            'hpRegen' => 10,
            'hpRegenPerLevel' => 1
        ]);
    }
}
