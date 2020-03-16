<?php

namespace spec\App\Repositories\RiotAPI\Champions;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Services\RiotAPI\ApiAdapterInterface;

class ChampionRepositorySpec extends ObjectBehavior
{
    public function let(
        LoggerInterface $log,
        ApiAdapterInterface $adapter
    ) {
        $this->beConstructedWith($log, $adapter);
    }

    public function it_can_fetch_data_by_id(
        ApiAdapterInterface $adapter,
        ResponseInterface $response
    ) {
        $params = [
            'version' => '1.0',
            'region' => 'euw'
        ];

        $adapter->fetch("static-data/v3/champions/1", $params)
            ->willReturn($response);

        $this->fetchById(1, $params)->shouldReturn($response);
    }
}
