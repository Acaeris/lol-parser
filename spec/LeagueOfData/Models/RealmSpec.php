<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;

class RealmSpec extends ObjectBehavior
{
    public function let()
    {
        $cdn = 'http://ddragon.leagueoflegends.com/cdn';
        $version = '7.4.3';
        $this->beConstructedWith(
            $cdn,
            $version
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Realm');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\RealmInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_a_cdn_url()
    {
        $this->sourceUrl()->shouldReturn('http://ddragon.leagueoflegends.com/cdn');
    }

    public function it_has_a_version_number()
    {
        $this->version()->shouldReturn('7.4.3');
    }

    public function it_can_be_converted_to_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'version' => '7.4.3',
            'cdn' => 'http://ddragon.leagueoflegends.com/cdn'
        ]);
    }
}
