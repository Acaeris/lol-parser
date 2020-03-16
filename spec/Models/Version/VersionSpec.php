<?php

namespace spec\App\Models\Version;

use PhpSpec\ObjectBehavior;

class VersionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('10.1.2');
    }

    public function it_has_a_data_key()
    {
        $this->getKeyData()->shouldReturn(['full_version' => '10.1.2']);
    }

    public function it_has_full_version_data()
    {
        $this->getFullVersion()->shouldReturn('10.1.2');
    }

    public function it_parses_season_data()
    {
        $this->getSeason()->shouldReturn(10);
    }

    public function it_parses_major_version_data()
    {
        $this->getMajorVersion()->shouldReturn(1);
    }

    public function it_parses_hotfix_data()
    {
        $this->getHotfix()->shouldReturn(2);
    }
}