<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;

class ChampionDefenseSpec extends ObjectBehavior
{
    public function let()
    {
        $armor = 19.22;
        $armorPerLevel = 4;
        $this->beConstructedWith('armor', $armor, $armorPerLevel);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionDefense');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionDefenseInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_is_a_resource()
    {
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ResourceInterface');
    }

    public function it_can_output_a_type_of_defense()
    {
        $this->type()->shouldReturn('armor');
    }

    public function it_can_output_a_base_value()
    {
        $this->baseValue()->shouldReturn(19.22);
    }

    public function it_can_output_a_value_per_level()
    {
        $this->increasePerLevel()->shouldReturn(4.0);
    }

    public function it_can_output_the_value_at_a_given_level()
    {
        $this->valueAtLevel(5)->shouldReturn(35.22);
    }

    public function it_requires_an_appropriate_resource_type()
    {
        $this->beConstructedWith('mana', 10, 1);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
