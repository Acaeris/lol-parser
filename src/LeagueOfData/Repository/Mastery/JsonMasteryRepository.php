<?php

namespace LeagueOfData\Repository\Mastery;

use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\FetchRepositoryInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Mastery\Mastery;

/**
 * Mastery object API Repository.
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class JsonMasteryRepository implements FetchRepositoryInterface
{

    /**
     * @var array Default parameters for API Query
     */
    private $apiDefaults = [
        'region' => 'euw',
        'tags' => 'all'
    ];

    /**
     * @var string API Endpoint
     */
    private $apiEndpoint = 'static-data/v3/masteries';

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $masteries;

    public function __construct(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->adapter = $adapter;
    }

    /**
     * Create the item object from JSON data
     *
     * @param  array $mastery
     * @return EntityInterface
     */
    public function create(array $mastery): EntityInterface
    {
        return new Mastery(
            $mastery['id'],
            $mastery['name'],
            $mastery['description'],
            $mastery['ranks'],
            $mastery['image']['full'],
            $mastery['masteryTree'],
            $mastery['version'],
            $mastery['region']
        );
    }

    /**
     * Fetch Items
     *
     * @param  array $params API parameters
     * @return array Item Objects
     */
    public function fetch(array $params): array
    {
        $this->masteries = [];

        $this->logger->debug("Fetching masteries from API");

        $params = array_merge($this->apiDefaults, $params);
        $response = $this->adapter->setOptions($this->apiEndpoint, $params)->fetch();
        $this->processResponse($response, $params);

        $this->logger->debug(count($this->masteries)." masteries fetched from API");

        return $this->masteries;
    }

    /**
     * Collection of Mastery objects
     *
     * @return array
     */
    public function transfer(): array
    {
        return $this->masteries;
    }

    /**
     * Convert response data into Mastery objects
     *
     * @param array $response
     * @param array $params
     */
    private function processResponse(array $response, array $params)
    {
        if (count($response) > 0 && $response !== false) {
            if (!isset($response['data'])) {
                $temp['data'] = [ $response ];
                $response = $temp;
            }

            foreach ($response['data'] as $mastery) {
                $mastery['version'] = $params['version'];
                $mastery['region'] = $params['region'];
                $this->masteries[$mastery['id']] = $this->create($mastery);
            }
        }
    }
}
