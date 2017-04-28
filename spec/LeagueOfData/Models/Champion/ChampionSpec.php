<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;

class ChampionSpec extends ObjectBehavior
{
    public function let(
        ChampionStatsInterface $stats
    ) {
        $this->beConstructedWith(1, "Test", "Test Character", "mp", ["Fighter", "Mage"], $stats, "6.21.1", "euw");
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

    public function it_has_all_data_available()
    {
        $this->getID()->shouldReturn(1);
        $this->name()->shouldReturn("Test");
        $this->title()->shouldReturn("Test Character");
        $this->resourceType()->shouldReturn('mp');
        $this->version()->shouldReturn('6.21.1');
        $this->tags()->shouldReturn(['Fighter', 'Mage']);
        $this->stats()->shouldReturnAnInstanceOf('LeagueOfData\Models\Interfaces\ChampionStatsInterface');
        $this->region()->shouldReturn('euw');
    }

    public function it_can_return_tags_as_simple_string()
    {
        $this->tagsAsString()->shouldReturn("Fighter|Mage");
    }
}
