<?php

namespace spec\LeagueOfData\Processor;

use Psr\Log\LoggerInterface;
use PhpSpec\ObjectBehavior;

class PlayerProcessorSpec extends ObjectBehavior
{
    public function let(LoggerInterface $log)
    {
        $this->beConstructedWith($log);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Processor\PlayerProcessor');
        $this->shouldImplement('LeagueOfData\Processor\ProcessorInterface');
    }
}
