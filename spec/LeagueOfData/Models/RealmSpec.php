<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;

class RealmSpec extends ObjectBehavior
{
    function let()
    {
        $cdn = 'http://ddragon.leagueoflegends.com/cdn';
        $version = '7.4.3';
        $region = 'euw';
        $this->beConstructedWith(
            $cdn,
            $version,
            $region
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Realm');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\RealmInterface');
    }

    function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    function it_has_a_cdn_url()
    {
        $this->sourceUrl()->shouldReturn('http://ddragon.leagueoflegends.com/cdn');
    }

    function it_has_a_version_number()
    {
        $this->version()->shouldReturn('7.4.3');
    }

    function it_has_a_region()
    {
        $this->region()->shouldReturn('euw');
    }
}
