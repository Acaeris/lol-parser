<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;

class RealmSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            'http://ddragon.leagueoflegends.com/cdn', // CDN
            '7.4.3' // Version
        );
    }

    public function it_is_initializable_and_immutable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Realm');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\RealmInterface');
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_contains_required_data()
    {
        $this->getSourceUrl()->shouldReturn('http://ddragon.leagueoflegends.com/cdn');
        $this->getVersion()->shouldReturn('7.4.3');
    }
}
