<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;

class VersionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith("6.21.1");
    }

    public function it_is_initializable_and_immutable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Version');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\VersionInterface');
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_contains_all_required_data()
    {
        $this->getFullVersion()->shouldReturn("6.21.1");
        $this->getSeason()->shouldReturn(6);
        $this->getMajorVersion()->shouldReturn(21);
        $this->getHotfix()->shouldReturn(1);
    }
}
