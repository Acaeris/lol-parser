<?php

namespace spec\LeagueOfData\Helpers\Format;

use PhpSpec\ObjectBehavior;

class MapNameSpec extends ObjectBehavior
{
    public function it_formats_the_map_value_into_matching_map_name()
    {
        $this->format(11)->shouldReturn("Summoner's Rift");
    }
}
