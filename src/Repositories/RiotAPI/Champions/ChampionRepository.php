<?php

namespace App\Repositories\RiotAPI\Champions;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use App\Models\Champions\Champion;
use App\Repositories\ChampionRepositoryInterface;
use App\Services\RiotAPI\ApiAdapterInterface;

/**
 * Champion Repository for Riot Games API
 *
 * @package App\Repositories\RiotAPI
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ChampionRepository implements ChampionRepositoryInterface
{
    /* @var LoggerInterface Logging Service */
    private $log;

    /* @var ApiAdapterInterface Riot API */
    private $api;

    /* @var string API Endpoint for Champion Data */
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
