<?php

namespace spec\LeagueOfData\Models\Json;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonSummonerSpec extends ObjectBehavior
{
    function let()
    {
        $json = json_decode(file_get_contents('data/summoner.json'));
        // Key is in test file but wouldn't normally be available.
        $this->beConstructedWith($json->{24121626});
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Json\JsonSummoner');
        $this->shouldImplement('LeagueOfData\Models\Summoner');
    }

    function it_has_an_id()
    {
        $this->id()->shouldReturn(24121626);
    }

    function it_has_a_name()
    {
        $this->name()->shouldReturn('Acaeris');
    }

    function it_has_a_level()
    {
        $this->level()->shouldReturn(30);
    }

    function it_has_an_icon()
    {
        $this->icon()->shouldReturn(779);
    }
}
