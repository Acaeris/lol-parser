<?php

namespace spec\LeagueOfData\Helpers\Format;

use PhpSpec\ObjectBehavior;

class SeasonSpec extends ObjectBehavior
{
    public function it_formats_the_season_value()
    {
        $this->format("SEASON2016")->shouldReturn(2016);
    }
}
