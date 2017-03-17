<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;

class VersionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith("6.21.1");
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Version');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\VersionInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_can_output_full_version()
    {
        $this->fullVersion()->shouldReturn("6.21.1");
    }

    public function it_can_output_the_season()
    {
        $this->season()->shouldReturn(6);
    }

    public function it_can_output_the_major_version()
    {
        $this->majorVersion()->shouldReturn(21);
    }

    public function it_can_output_the_hotfix_version()
    {
        $this->hotfix()->shouldReturn(1);
    }
}
