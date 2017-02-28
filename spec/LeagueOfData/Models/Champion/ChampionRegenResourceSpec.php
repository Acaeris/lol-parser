<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;

class ChampionRegenResourceSpec extends ObjectBehavior
{
    function let()
    {
        $health = 511.68;
        $healthPerLevel = 76;
        $healthRegen = 5.424;
        $healthRegenPerLevel = 0.55;
        $this->beConstructedWith('hp', $health, $healthPerLevel, 5.424, 0.55);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionRegenResource');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface');
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
        $this->baseValue()->shouldReturn(511.68);
    }

    function it_can_output_a_value_per_level()
    {
        $this->increasePerLevel()->shouldReturn(76.0);
    }

    function it_can_output_the_value_at_a_given_level()
    {
        $this->valueAtLevel(5)->shouldReturn(815.68);
    }

    function it_can_output_a_regen_base_value()
    {
        $this->regenBaseValue()->shouldReturn(5.424);
    }

    function it_can_output_a_regen_per_level_value()
    {
        $this->regenIncreasePerLevel()->shouldReturn(0.55);
    }

    function it_can_output_a_regen_value_at_a_given_level()
    {
        $this->regenAtLevel(5)->shouldReturn(7.624);
    }

    function it_can_output_the_data_as_an_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'hp' => 511.68,
            'hpPerLevel' => 76.0,
            'hpRegen' => 5.424,
            'hpRegenPerLevel' => 0.55
        ]);
    }
}
