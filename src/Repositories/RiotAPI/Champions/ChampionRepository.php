<?php

namespace App\Repositories\RiotAPI\Champions;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use App\Models\Champions\Champion;
use App\Repositories\ChampionRepositoryInterface;
use App\Services\RiotAPI\ApiAdapterInterface;

/**
 * Repository for Riot Games Champion API
 *
 * @package LeagueOfData\Repositories\RiotAPI
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ChampionRepository implements ChampionRepositoryInterface
{
    /**
     * Logging Service
     *
     * @var LoggerInterface
     */
    private $log;

    /**
     * Riot API
     *
     * @var ApiAdapterInterface
     */
    private $api;

    /**
     * API Endpoint for Champion Data
     *
     * @var string
     */
    private $apiEndpoint = 'static-data/v3/champions';

    public function __construct(
        LoggerInterface $log,
        ApiAdapterInterface $api
    ) {
        $this->log = $log;
        $this->api = $api;
    }

    public function save(Champion $champion)
    {

    }

    public function getAll(array $params): ResponseInterface
    {
        return $this->api->fetch($this->apiEndpoint, $params);
    }

    public function fetchById(int $championId, array $params): ResponseInterface
    {
        return $this->api->fetch(
            $this->apiEndpoint.'/'.$championId,
            $params
        );
    }
}
