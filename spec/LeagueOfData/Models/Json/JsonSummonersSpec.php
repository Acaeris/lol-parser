<?php

namespace spec\LeagueOfData\Models\Json;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use LeagueOfData\Adapters\AdapterInterface;

class JsonSummonersSpec extends ObjectBehavior
{
    function let(AdapterInterface $adapter)
    {
        $this->beConstructedWith($adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Json\JsonSummoners');
        $this->shouldImplement('LeagueOfData\Models\Summoners');
    }
}
