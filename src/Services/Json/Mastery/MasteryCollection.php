<?php

namespace App\Services\Json\Mastery;

use App\Mappers\Masteries\MasteryMapper;
use Psr\Log\LoggerInterface;
use App\Services\FetchServiceInterface;
use App\Adapters\AdapterInterface;
use App\Models\Masteries\Mastery;

/**
 * Mastery object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class MasteryCollection implements FetchServiceInterface
{

    /* @var array Default parameters for API Query */
    private $apiDefaults = [
        'region' => 'euw',
        'tags' => 'all'
    ];

    /* @var string API Endpoint */
    private $apiEndpoint = 'static-data/v3/masteries';

    /* @var AdapterInterface API Adapter */
    private $adapter;

    /* @var LoggerInterface Logger */
    private $logger;

    /* @var MasteryMapper Mastery object mapper */
    private $masteryMapper;

    /* @var Mastery[] Mastery collection */
    private $masteries;

    public function __construct(AdapterInterface $adapter, LoggerInterface $logger, MasteryMapper $masteryMapper)
    {
        $this->logger = $logger;
        $this->adapter = $adapter;
        $this->masteryMapper = $masteryMapper;
    }

    /**
     * Fetch Items
     *
     * @param array $params API parameters
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
                $this->masteries[$mastery['id']] = $this->masteryMapper->mapFromArray($mastery);
            }
        }
    }
}
