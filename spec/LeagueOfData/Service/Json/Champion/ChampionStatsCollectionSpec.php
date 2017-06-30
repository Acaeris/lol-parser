<?php

namespace spec\LeagueOfData\Service\Json\Champion;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;

class ChampionStatsCollectionSpec extends ObjectBehavior
{
    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\Champion\ChampionStatsCollection');
        $this->shouldImplement('LeagueOfData\Service\FetchServiceInterface');
    }
}
