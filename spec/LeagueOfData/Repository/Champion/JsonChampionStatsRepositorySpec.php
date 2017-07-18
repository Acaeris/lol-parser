<?php

namespace spec\LeagueOfData\Repository\Champion;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;

class JsonChampionStatsRepositorySpec extends ObjectBehavior
{
    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Champion\JsonChampionStatsRepository');
        $this->shouldImplement('LeagueOfData\Repository\FetchRepositoryInterface');
    }
}
