<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;

class ChampionSpec extends ObjectBehavior
{
    public function let(
        ChampionStatsInterface $stats
    ) {
        $this->beConstructedWith(1, "Test", "Test Character", "mp", ["Fighter", "Mage"], $stats, "6.21.1");
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\Champion');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_an_id()
    {
        $this->getID()->shouldReturn(1);
    }

    public function it_has_a_name()
    {
        $this->name()->shouldReturn("Test");
    }

    public function it_has_a_title()
    {
        $this->title()->shouldReturn("Test Character");
    }

    public function it_has_a_resource_type()
    {
        $this->resourceType()->shouldReturn('mp');
    }

    public function it_has_a_client_version()
    {
        $this->version()->shouldReturn('6.21.1');
    }

    public function it_has_tags()
    {
        $this->tags()->shouldReturn(['Fighter', 'Mage']);
    }

    public function it_can_return_tags_as_simple_string()
    {
        $this->tagsAsString()->shouldReturn("Fighter|Mage");
    }

    public function it_has_stats()
    {
        $this->stats()->shouldReturnAnInstanceOf('LeagueOfData\Models\Interfaces\ChampionStatsInterface');
    }

    public function it_can_be_converted_to_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'champion_id' => 1,
            'champion_name' => "Test",
            'title' => "Test Character",
            'resource_type' => "mp",
            'tags' => "Fighter|Mage",
            'version' => "6.21.1"
        ]);
    }
}
