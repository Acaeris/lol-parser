<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;

class ChampionSpec extends ObjectBehavior
{
    function let(
        ChampionStatsInterface $stats
    ) {
        $this->beConstructedWith(1, "Test", "Test Character", "mp", "Fighter|Mage", $stats, "6.21.1");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion');
    }

    function it_has_an_id()
    {
        $this->id()->shouldReturn(1);
    }

    function it_has_a_name()
    {
        $this->name()->shouldReturn("Test");
    }

    function it_has_a_title()
    {
        $this->title()->shouldReturn("Test Character");
    }

    function it_has_a_client_version()
    {
        $this->version()->shouldReturn('6.21.1');
    }
}
