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
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionInterface');
    }

    function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
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

    function it_has_tags()
    {
        $this->tags()->shouldReturn(['Fighter', 'Mage']);
    }

    function it_can_return_tags_in_original_format()
    {
        $this->tagsAsString()->shouldReturn("Fighter|Mage");
    }

    function it_has_stats()
    {
        $this->stats()->shouldReturnAnInstanceOf('LeagueOfData\Models\Interfaces\ChampionStatsInterface');
    }
}
