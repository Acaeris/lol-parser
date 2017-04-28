<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;

class ChampionRegenResourceSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            511.68, // Base value
            76, // Value increase per level
            5.424, // Base regen value
            0.55 // Regen value increase per level
        );
    }

    public function it_is_initializable_immutable_and_a_resource()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionRegenResource');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface');
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ResourceInterface');
    }

    public function it_has_all_data_available()
    {
        $this->getBaseValue()->shouldReturn(511.68);
        $this->getIncreasePerLevel()->shouldReturn(76.0);
        $this->getRegenBaseValue()->shouldReturn(5.424);
        $this->getRegenIncreasePerLevel()->shouldReturn(0.55);
    }

    public function it_can_output_the_value_at_a_given_level()
    {
        $this->calculateValueAtLevel(5)->shouldReturn(815.68);
    }

    public function it_can_output_a_regen_value_at_a_given_level()
    {
        $this->calculateRegenAtLevel(5)->shouldReturn(7.624);
    }
}
