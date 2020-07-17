<?php

namespace App\Repositories\RiotAPI\ChampionMastery;

use App\Mappers\ChampionMastery\ChampionMasteryMapper;
use App\Services\RiotAPI\ApiAdapterInterface;
use Psr\Log\LoggerInterface;

/**
 * Champion Mastery Repository for Riot Games API
 *
 * @package App\Repositories\RiotAPI
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ChampionMasteryRepository
{
    /* @var LoggerInterface Logging Service */
    private $log;

    /* @var ApiAdapterInterface Riot API */
    private $api;

    /* @var ChampionMasteryMapper Object mapper for Champion Mastery */
    private $mapper;

    /* @var string Root endpoint for the calls in this repository */
    private $apiEndpoint = 'champion-mastery/v4/';

    public function __construct(LoggerInterface $log, ApiAdapterInterface $api, ChampionMasteryMapper $mapper)
    {
        $this->log = $log;
        $this->api = $api;
        $this->mapper = $mapper;
    }

    /**
     * Get all champion mastery entries sorted by number of champion points descending.
     * @param int $summonerId Encrypted Summoner ID
     * @param array $params Routing Parameters
     * @return ChampionMastery[] Collection of Masteries
     */
    public function fetchBySummonerId(int $summonerId, array $params): array
    {
        // TODO: Validate Parameters

        return $this->mapper->mapFromApiData($this->api->fetch(
            $this->apiEndpoint . 'champion-masteries/by-summoner/' . $summonerId,
            $params
        ));
    }

    /**
     * Get a champion mastery by player ID and champion ID.
     * @param int $summonerId Encrypted Summoner ID
     * @param int $championId Champion ID
     * @param array $params Routing Parameters
     * @return ChampionMastery[] Collection of Masteries
     */
    public function fetchBySummonerAndChampionIds(int $summonerId, int $championId, array $params): array
    {
        // TODO: Validate Parameters

        return $this->mapper->mapFromApiData($this->api->fetch(
            $this->apiEndpoint . 'champion-masteries/by-summoner/' . $summonerId . '/by-champion/' . $championId,
            $params
        ));
    }

    /**
     * Get a player's total champion mastery score, which is the sum of individual champion mastery levels.
     * @param int $summonerId Encrypted Summoner ID
     * @param array $params Routing Parameters
     * @return ChampionMastery[] Collection of Masteries
     */
    public function fetchScoreBySummonerId(int $summonerId, array $params): array
    {
        // TODO: Validate Parameters

        return $this->mapper->mapFromApiData($this->api->fetch(
            $this->apiEndpoint . 'scores/by-summoner/' . $summonerId,
            $params
        ));
    }
}
