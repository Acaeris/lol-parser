<?php

namespace spec\App\Services\Json\Champion;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use App\Adapters\AdapterInterface;

class ChampionStatsCollectionSpec extends ObjectBehavior
{
    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('App\Services\Json\Champion\ChampionStatsCollection');
        $this->shouldImplement('App\Services\FetchServiceInterface');
    }
}
