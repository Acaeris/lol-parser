<?php

namespace spec\LeagueOfData\Helpers\Format;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MapNameSpec extends ObjectBehavior
{
    function it_formats_the_map_value_into_matching_map_name()
    {
        $this->format(11)->shouldReturn("Summoner's Rift");
    }
}
