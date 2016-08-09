<?php

namespace spec\LeagueOfData\Processor;

use Psr\Log\LoggerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PlayerProcessorSpec extends ObjectBehavior
{
    function let(LoggerInterface $log)
    {
        $this->beConstructedWith($log);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Processor\PlayerProcessor');
        $this->shouldImplement('LeagueOfData\Processor\ProcessorInterface');
    }
}
