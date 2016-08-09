<?php

namespace spec\LeagueOfData\Models\Json;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonMatchHistorySpec extends ObjectBehavior
{
    function let()
    {
        $json = json_decode(file_get_contents('data/matchlist.json'));
        $this->beConstructedWith($json->matches[0]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Json\JsonMatchHistory');
        $this->shouldImplement('LeagueOfData\Models\MatchHistory');
    }

    function it_has_an_id()
    {
        $this->id()->shouldReturn(2742694506);
    }

    function it_has_a_region()
    {
        $this->region()->shouldReturn('EUW');
    }
}
