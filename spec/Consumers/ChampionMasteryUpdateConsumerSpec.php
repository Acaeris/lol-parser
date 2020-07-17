<?php

namespace spec\App\Consumers;

use App\Mappers\ChampionMastery\ChampionMasteryMapper;
use App\Models\ChampionMastery\ChampionMastery;
use App\Repositories\RiotAPI\ChampionMastery\ChampionMasteryRepository as ApiRepository;
use App\Repositories\Database\ChampionMastery\ChampionMasteryRepository as DbRepository;
use PhpAmqpLib\Message\AMQPMessage;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class ChampionMasteryUpdateConsumerSpec extends ObjectBehavior
{
    private $mockDbData = [
        'lastUpdated' => '2019-01-01 00:00:00'
    ];

    public function let(
        LoggerInterface $log,
        ApiRepository $apiRepository,
        DbRepository $dbRepository
    ) {
        $this->beConstructedWith($log, $apiRepository, $dbRepository);
    }

    public function it_can_consume_messages_from_the_message_queue(
        AMQPMessage $message,
        DbRepository $dbRepository
    ) {
        $message->body = ['summonerId' => 1, 'region' => 'euw'];
        $dbRepository->fetchBySummonerId(1, 'euw')->willReturn($this->mockDbData);

        $this->execute($message)->shouldReturn(true);
    }

    public function it_can_update_champion_mastery_entries(
        AMQPMessage $message,
        ApiRepository $apiRepository,
        DbRepository $dbRepository,
        ChampionMastery $championMastery
    ) {
        $message->body = ['summonerId' => 1, 'region' => 'euw'];

        $apiRepository->fetchBySummonerId(1, $message->body)->willReturn([$championMastery]);
        $dbRepository->store([$championMastery])->shouldBeCalled();

        $this->update($message)->shouldReturn(true);
    }
}