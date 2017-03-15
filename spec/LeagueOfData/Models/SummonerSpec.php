<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;

class SummonerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(1, 'Acaeris', 30, 250, '2017-01-01 00:00:00');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Summoner');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\SummonerInterface');
    }

    function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    function it_can_be_converted_to_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'id' => 1,
            'name' => 'Acaeris',
            'level' => 30,
            'icon' => 250,
            'revisionDate' => '2017-01-01 00:00:00'
        ]);
    }

    function it_can_output_the_summoner_id()
    {
        $this->getID()->shouldReturn(1);
    }

    function it_can_output_the_summoner_name()
    {
        $this->getName()->shouldReturn('Acaeris');
    }

    function it_can_output_the_summoner_level()
    {
        $this->getLevel()->shouldReturn(30);
    }

    function it_can_output_the_summoner_icon_id()
    {
        $this->getIconID()->shouldReturn(250);
    }

    function it_can_output_the_revision_date()
    {
        $this->getRevisionDate()->shouldReturn('2017-01-01 00:00:00');
    }
}