<?php

namespace LeagueOfData\Repository\Match;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Repository\FetchRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Match\Match;

class JsonMatchRepository implements FetchRepositoryInterface
{
    /**
     * @var array Default parameters for API query
     */
    private $apiDefaults = ['region' => 'euw'];

    /**
     * @var string API Endpoint
     */
    private $apiEndpoint = 'match/v3/matches';

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
    private $matches;

    public function __construct(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->adapter = $adapter;
        $this->logger = $logger;
    }

    /**
     * Create the match object from array data
     *
     * @param array $match
     * @return EntityInterface
     */
    public function create(array $match): EntityInterface
    {
        return new Match(
            $match['gameId'],
            $match['region'],
            $match['gameMode'],
            $match['gameType'],
            $match['gameDuration'],
            $match['gameVersion']
        );
    }

    /**
     * Fetch Matches
     *
     * @param array Fetch parameters
     * @return array Match Objects
     */
    public function fetch(array $params): array
    {
        $this->matches = [];
        $this->logger->debug("Fetching match from API");

        $query = $this->apiEndpoint.'/'.$params['match_id'];
        $params = array_merge($this->apiDefaults, $params);
        $response = $this->adapter->setOptions($query, $params)->fetch();
        $this->processResponse($response, $params);

        $this->logger->debug(count($this->matches)." matches fetch from API");

        return $this->matches;
    }

    /**
     * Collection of Match objects
     *
     * @return array
     */
    public function transfer(): array
    {
        return $this->matches;
    }

    /**
     * Convert response data into match objects
     *
     * @param array $response
     * @param array $params
     */
    private function processResponse(array $response, array $params)
    {
        if (count($response) > 0 && $response !== false) {
            $response['region'] = $params['region'];
            $this->matches[$response['gameId']] = $this->create($response);
        }
    }
}
