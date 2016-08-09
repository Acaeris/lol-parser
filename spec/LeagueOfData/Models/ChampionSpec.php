<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChampionSpec extends ObjectBehavior
{
    function let(\stdClass $json)
    {
        $json = json_decode(file_get_contents('data/champion1.json'), true);
        $this->beConstructedWith($json);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion');
        $this->shouldImplement('LeagueOfData\Models\ChampionInterface');
    }

    function it_has_an_id()
    {
        $this->id()->shouldReturn(266);
    }

    function it_has_a_name()
    {
        $this->name()->shouldReturn('Aatrox');
    }

    function it_has_a_title()
    {
        $this->title()->shouldReturn('the Darkin Blade');
    }
}
