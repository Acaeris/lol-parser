<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;

class ChampionSpec extends ObjectBehavior
{
    function let(
        ChampionStatsInterface $stats
    ) {
        $stats->toArray()->willReturn(['stats' => 100]);
        $this->beConstructedWith(1, "Test", "Test Character", "mp", ["Fighter", "Mage"], $stats, "6.21.1");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\Champion');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionInterface');
    }

    function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    function it_has_an_id()
    {
        $this->getID()->shouldReturn(1);
    }

    function it_has_a_name()
    {
        $this->name()->shouldReturn("Test");
    }

    function it_has_a_title()
    {
        $this->title()->shouldReturn("Test Character");
    }

    function it_has_a_resource_type()
    {
        $this->resourceType()->shouldReturn('mp');
    }

    function it_has_a_client_version()
    {
        $this->version()->shouldReturn('6.21.1');
    }

    function it_has_tags()
    {
        $this->tags()->shouldReturn(['Fighter', 'Mage']);
    }

    function it_can_return_tags_as_simple_string()
    {
        $this->tagsAsString()->shouldReturn("Fighter|Mage");
    }

    function it_has_stats()
    {
        $this->stats()->shouldReturnAnInstanceOf('LeagueOfData\Models\Interfaces\ChampionStatsInterface');
    }

    function it_can_be_converted_to_array_for_storage(ChampionStatsInterface $stats)
    {
        $stats->toArray()->shouldBeCalled();
        $this->toArray()->shouldReturn([
            'id' => 1,
            'name' => "Test",
            'title' => "Test Character",
            'resourceType' => "mp",
            'tags' => "Fighter|Mage",
            'version' => "6.21.1",
            'stats' => 100
        ]);
    }
}
