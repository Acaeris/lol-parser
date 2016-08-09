<?php

namespace spec\LeagueOfData\Helpers\Format;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DurationSpec extends ObjectBehavior
{
    function it_formats_the_duration_value_into_time()
    {
        $this->format(2492)->shouldReturn("41:32");
    }
}
