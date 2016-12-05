<?php

namespace spec\LeagueOfData\Processor;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChampionProcessorSpec extends ObjectBehavior
{
    function let(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->beConstructedWith($adapter, $log);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Processor\ChampionProcessor');
        $this->shouldImplement('LeagueOfData\Processor\ProcessorInterface');
    }
}
