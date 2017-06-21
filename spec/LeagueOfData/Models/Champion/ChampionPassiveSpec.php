<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;

class ChampionPassiveSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            1, // Champion ID
            "Pyromania", // Passive Name
            "Annie_Passive", // Icon image filename
            "Test Description", // Desciption
            "7.9.1", // Version
            "euw" // Region
        );
    }

    public function it_is_initializable_and_immutable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionPassive');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionPassiveInterface');
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_required_data()
    {
        $this->getChampionID()->shouldReturn(1);
        $this->getPassiveName()->shouldReturn("Pyromania");
        $this->getImageName()->shouldReturn("Annie_Passive");
        $this->getDescription()->shouldReturn("Test Description");
        $this->getVersion()->shouldReturn("7.9.1");
        $this->getRegion()->shouldReturn("euw");
    }
}
