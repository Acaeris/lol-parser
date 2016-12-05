<?php

namespace spec\LeagueOfData\Helpers\Format;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SeasonSpec extends ObjectBehavior
{
    function it_formats_the_season_value()
    {
        $this->format("SEASON2016")->shouldReturn(2016);
    }
}
