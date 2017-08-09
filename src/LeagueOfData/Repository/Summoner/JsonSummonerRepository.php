<?php

namespace LeagueOfData\Repository\Summoner;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Repository\FetchRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Summoner\Summoner;

class JsonSummonerRepository implements FetchRepositoryInterface
{
    /**
     * @var array Default parameters for API query
     */
    private $apiDefaults = ['region' => 'euw'];

    /**
     * @var string API Endpoint
     */
    private $apiEndpoint = 'summoner/v3/summoners';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var array
     */
    private $summoners;

    public function __construct(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->adapter = $adapter;
        $this->logger = $logger;
    }

    /**
     * Create the summoner object from array data
     *
     * @param  array $summoner
     * @return EntityInterface
     */
    public function create(array $summoner): EntityInterface
    {
        return new Summoner(
            $summoner['id'],
            $summoner['accountId'],
            $summoner['name'],
            $summoner['summonerLevel'],
            $summoner['profileIconId'],
            "@".substr($summoner['revisionDate'], 0, 10),
            $summoner['region']
        );
    }

    /**
     * Fetch Summoners
     *
     * @param  array Fetch parameters
     * @return array Summoner Objects
     */
    public function fetch(array $params): array
    {
        $this->summoners = [];
        $this->logger->debug("Fetching summoner from API");

        $query = $this->apiEndpoint . '/' . $this->queryParams($params);
        $params = array_merge($this->apiDefaults, $params);
        $response = $this->adapter->setOptions($query, $params)->fetch();
        $this->processResponse($response, $params);

        $this->logger->debug(count($this->summoners)." summoners fetched from API");

        return $this->summoners;
    }

    /**
     * Collection of Summoner objects
     *
     * @return array
     */
    public function transfer(): array
    {
        return $this->summoners;
    }

    /**
     * Determine which API query to use based on the supplied parameters
     *
     * @param  array $params
     * @return string
     * @throws \Exception
     */
    private function queryParams(array $params) : string
    {
        if (isset($params['summoner_id'])) {
            return $params['summoner_id'];
        }
        if (isset($params['summoner_name'])) {
            return "by-name/".$params['summoner_name'];
        }
        if (isset($params['account_id'])) {
            return "by-account/".$params['account_id'];
        }
        throw new \Exception('No fetch parameter supplied for Summoner API');
    }

    /**
     * Convert response data into Summoner objects
     *
     * @param array $response
     * @param array $params
     */
    private function processResponse(array $response, array $params)
    {
        if (count($response) > 0 && $response !== false) {
            $response['region'] = $params['region'];
            $this->summoners[$response['id']] = $this->create($response);
        }
    }
}
