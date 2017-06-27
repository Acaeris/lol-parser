<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\FetchServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Rune\Rune;
use LeagueOfData\Entity\Stat;

/**
 * Rune object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonRunes implements FetchServiceInterface
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
    private $apiEndpoint = 'static-data/v3/runes';

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
    private $runes;

    public function __construct(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->adapter = $adapter;
    }

    /**
     * Create the item object from JSON data
     *
     * @param array $rune
     * @return ItemInterface
     */
    public function create(array $rune): EntityInterface
    {
        return new Rune(
            $rune['id'],
            $rune['name'],
            $rune['description'],
            $rune['image']['full'],
            $this->createStats($rune),
            $rune['tags'],
            $rune['version'],
            $rune['region']
        );
    }

    /**
     * Fetch Items
     *
     * @param array $params API parameters
     * @return array Item Objects
     */
    public function fetch(array $params): array
    {
        $this->runes = [];

        $this->logger->debug("Fetching runes from API");

        $response = $this->adapter->fetch($this->apiEndpoint, array_merge($this->apiDefaults, $params));
        $this->processResponse($response, array_merge($this->apiDefaults, $params));

        $this->logger->debug(count($this->runes)." runes fetched from API");

        return $this->runes;
    }

    /**
     * Collection of Item objects
     *
     * @return array
     */
    public function transfer(): array
    {
        return $this->runes;
    }

    /**
     * Create the rune's stat objects from JSON data
     *
     * @param array $rune
     * @return array
     */
    private function createStats(array $rune) : array
    {
        $stats = [];

        foreach ($rune['stats'] as $key => $value) {
            $stats[] = new Stat($key, $value);
        }

        return $stats;
    }

    /**
     * Convert response data into Rune objects
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

            foreach ($response['data'] as $rune) {
                $rune['version'] = $params['version'];
                $rune['region'] = $params['region'];
                $this->runes[$rune['id']] = $this->create($rune);
            }
        }
    }
}
