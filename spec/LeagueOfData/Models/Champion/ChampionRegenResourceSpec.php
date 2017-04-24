<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;

class ChampionRegenResourceSpec extends ObjectBehavior
{
    public function let()
    {
        $health = 511.68;
        $healthPerLevel = 76;
        $healthRegen = 5.424;
        $healthRegenPerLevel = 0.55;
        $this->beConstructedWith('hp', $health, $healthPerLevel, $healthRegen, $healthRegenPerLevel);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionRegenResource');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_is_a_resource()
    {
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ResourceInterface');
    }

    public function it_can_output_a_type_of_resource()
    {
        $this->type()->shouldReturn("hp");
    }

    public function it_can_output_a_base_value()
    {
        $this->baseValue()->shouldReturn(511.68);
    }

    public function it_can_output_a_value_per_level()
    {
        $this->increasePerLevel()->shouldReturn(76.0);
    }

    public function it_can_output_the_value_at_a_given_level()
    {
        $this->valueAtLevel(5)->shouldReturn(815.68);
    }

    public function it_can_output_a_regen_base_value()
    {
        $this->regenBaseValue()->shouldReturn(5.424);
    }

    public function it_can_output_a_regen_per_level_value()
    {
        $this->regenIncreasePerLevel()->shouldReturn(0.55);
    }

    public function it_can_output_a_regen_value_at_a_given_level()
    {
        $this->regenAtLevel(5)->shouldReturn(7.624);
    }
}
