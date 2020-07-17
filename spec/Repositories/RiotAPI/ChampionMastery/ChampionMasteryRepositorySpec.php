<?php

namespace spec\App\Repositories\RiotAPI\ChampionMastery;

use App\Mappers\ChampionMastery\ChampionMasteryMapper;
use App\Models\ChampionMastery\ChampionMastery;
use App\Services\RiotAPI\ApiAdapterInterface;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class ChampionMasteryRepositorySpec extends ObjectBehavior
{
    private $params = ['region' => 'euw'];

    public function let(
        LoggerInterface $logger,
        ApiAdapterInterface $adapter,
        ChampionMasteryMapper $mapper
    ) {
        $this->beConstructedWith($logger, $adapter, $mapper);
    }

    public function it_can_fetch_data_by_summoner_id(
        ApiAdapterInterface $adapter,
        ResponseInterface $response,
        ChampionMasteryMapper $mapper,
        ChampionMastery $mastery
    ) {
        $adapter->fetch('champion-mastery/v4/champion-masteries/by-summoner/1', $this->params)
            ->willReturn($response);
        $mapper->mapFromApiData($response)->willReturn([$mastery]);

        $this->fetchBySummonerId(1, $this->params)->shouldReturn([$mastery]);
    }

    public function it_can_fetch_data_by_summoner_id_and_champion_id(
        ApiAdapterInterface $adapter,
        ResponseInterface $response,
        ChampionMasteryMapper $mapper,
        ChampionMastery $mastery
    ) {
        $adapter->fetch('champion-mastery/v4/champion-masteries/by-summoner/1/by-champion/2', $this->params)
            ->willReturn($response);
        $mapper->mapFromApiData($response)->willReturn([$mastery]);

        $this->fetchBySummonerAndChampionIds(1, 2, $this->params)->shouldReturn([$mastery]);
    }

    public function it_can_fetch_mastery_score_by_summoner_id(
        ApiAdapterInterface $adapter,
        ResponseInterface $response,
        ChampionMasteryMapper $mapper,
        ChampionMastery $mastery
    ) {
        $adapter->fetch('champion-mastery/v4/scores/by-summoner/1', $this->params)->willReturn($response);
        $mapper->mapFromApiData($response)->willReturn([$mastery]);

        $this->fetchScoreBySummonerId(1, $this->params)->shouldReturn([$mastery]);
    }
}