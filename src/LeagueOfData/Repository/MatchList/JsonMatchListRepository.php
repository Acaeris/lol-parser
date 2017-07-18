<?php

namespace LeagueOfData\Repository\MatchList;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Repository\FetchRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Match\Match;

class JsonMatchListRepository implements FetchRepositoryInterface
{
    /**
     * @var array Default parameters for API query
     */
    private $apiDefaults = ['region' => 'euw'];

    /**
     * @var string API Endpoint
     */
    private $apiEndpoint = 'match/v3/matchlists/by-account';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var array Match collection
     */
    private $matches = [];

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
            $match['region']
        );
    }

    /**
     * Fetch Match List
     *
     * @param array Fetch parameters
     * @return array Match Objects
     */
    public function fetch(array $params): array
    {
        $this->matches = [];
        $this->logger->debug("Fetching match list from API");

        $query = $this->apiEndpoint.'/'.$params['account_id'];
        if (!isset($params['all']) || !$params['all']) {
            $query .= "/recent";
            unset($params['all']);
        }
        $params = array_merge($this->apiDefaults, $params);
        $response = $this->adapter->setOptions($query, $params)->fetch();
        $this->processResponse($response, $params);

        $this->logger->debug(count($this->matches)." matches fetched from API");

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
     * Convert response data into Match objects
     *
     * @param array $response
     * @param array $params
     */
    private function processResponse(array $response, array $params)
    {
        if (count($response) > 0 && $response !== false) {
            if (!isset($response['matches'])) {
                $temp['matches'] = [ $response ];
                $response = $temp;
            }

            foreach ($response['matches'] as $match) {
                $match['region'] = $params['region'];
                $this->matches[$match['gameId']] = $this->create($match);
            }
        }
    }
}
