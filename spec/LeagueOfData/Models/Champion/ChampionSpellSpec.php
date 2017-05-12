<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;

class ChampionSpellSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            1, // Champion ID
            2, // Spell ID
            "Disintegrate", // Spell Name
            "Q", // Spell control key
            "Disintegrate", // Icon image filename
            5, // Max rank
            "Test Description", // Description
            "Test Tooltip" // Tooltip
        );
    }

    public function it_is_initializable_and_immutable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionSpell');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionSpellInterface');
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_required_data()
    {
        $this->getChampionID()->shouldReturn(1);
        $this->getSpellID()->shouldReturn(2);
        $this->getSpellName()->shouldReturn("Disintegrate");
        $this->getKey()->shouldReturn("Q");
        $this->getImageName()->shouldReturn("Disintegrate");
        $this->getMaxRank()->shouldReturn(5);
        $this->getDescription()->shouldReturn("Test Description");
        $this->getTooltip()->shouldReturn("Test Tooltip");
    }
}
