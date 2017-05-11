<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;

class ChampionSpec extends ObjectBehavior
{
    public function let(
        ChampionStatsInterface $stats
    ) {
        $this->beConstructedWith(
            1, // Champion ID
            "Test", // Champion Name
            "Test Character", // Champion Title
            "mp", // Resource Type
            ["Fighter", "Mage"], // Tags
            $stats, // Stats
            "Test", // Image Name
            "6.21.1", // Version
            "euw" // Region
        );
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
        $this->getName()->shouldReturn("Test");
        $this->getTitle()->shouldReturn("Test Character");
        $this->getImageName()->shouldReturn("Test");
        $this->getResourceType()->shouldReturn('mp');
        $this->getVersion()->shouldReturn('6.21.1');
        $this->getTags()->shouldReturn(['Fighter', 'Mage']);
        $this->getStats()->shouldReturnAnInstanceOf('LeagueOfData\Models\Interfaces\ChampionStatsInterface');
        $this->getRegion()->shouldReturn('euw');
    }

    public function it_can_return_tags_as_simple_string()
    {
        $this->getTagsAsString()->shouldReturn("Fighter|Mage");
    }
}
