<?php

namespace spec\LeagueOfData\Service\Json;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;

class JsonChampionStatsSpec extends ObjectBehavior
{
    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\JsonChampionStats');
        $this->shouldImplement('LeagueOfData\Service\FetchServiceInterface');
    }
}
