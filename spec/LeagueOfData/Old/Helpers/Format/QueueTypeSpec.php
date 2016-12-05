<?php

namespace spec\LeagueOfData\Helpers\Format;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QueueTypeSpec extends ObjectBehavior
{
    function it_formats_the_the_queue_type()
    {
        $this->format("RANKED_SOLO_5x5")->shouldReturn("Solo Queue Ranked Draft");
    }
}
