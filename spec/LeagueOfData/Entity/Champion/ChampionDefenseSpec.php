<?php

namespace spec\LeagueOfData\Entity\Champion;

use PhpSpec\ObjectBehavior;

class ChampionDefenseSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            'armor', // Defense Type
            19.22, // Base value
            4 // Value increase per level
        );
    }

    public function it_is_initializable_immutable_and_a_resource()
    {
        $this->shouldHaveType('LeagueOfData\Entity\Champion\ChampionDefense');
        $this->shouldImplement('LeagueOfData\Entity\Champion\ChampionDefenseInterface');
        $this->shouldImplement('LeagueOfData\Entity\ResourceInterface');
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_data_available()
    {
        $this->getType()->shouldReturn('armor');
        $this->getBaseValue()->shouldReturn(19.22);
        $this->getIncreasePerLevel()->shouldReturn(4.0);
    }

    public function it_can_calculate_the_value_at_a_given_level()
    {
        $this->calculateValueAtLevel(5)->shouldReturn(35.22);
    }

    public function it_requires_an_appropriate_resource_type()
    {
        $this->beConstructedWith('mana', 10, 1); // Not a valid defense resource
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
