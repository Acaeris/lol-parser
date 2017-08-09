<?php

namespace LeagueOfData\Repository\Item;

use Psr\Log\LoggerInterface;
use LeagueOfData\Repository\FetchRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Item\Item;
use LeagueOfData\Entity\Item\ItemInterface;
use LeagueOfData\Entity\Stat;
use LeagueOfData\Adapters\AdapterInterface;

/**
 * Item object API Repository.
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class JsonItemRepository implements FetchRepositoryInterface
{
    /**
     * @var array Default parameters for API query
     */
    private $apiDefaults = [
        'region' => 'euw',
        'tags' => 'all'
    ];

    /**
     * @var string API Endpoint
     */
    private $apiEndpoint = 'static-data/v3/items';

    /**
     * @var AdapterInterface API adapter
     */
    private $source;

    /**
     * @var LoggerInterface logger
     */
    private $log;

    /**
     * @var array Item Objects
     */
    private $items;

    /**
     * Setup Item repository
     *
     * @param AdapterInterface $adapter
     * @param LoggerInterface  $log
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->source = $adapter;
        $this->log = $log;
    }

    /**
     * Create the item object from JSON data
     *
     * @param  array $item
     * @return ItemInterface
     */
    public function create(array $item) : EntityInterface
    {
        return new Item(
            $item['id'],
            $item['name'] ?? '',
            $item['description'] ?? '',
            $item['gold']['total'] ?? '',
            $item['gold']['sell'] ?? '',
            $this->createStats($item),
            $item['version'],
            $item['region']
        );
    }

    /**
     * Fetch Items
     *
     * @param  array $params API parameters
     * @return array Item Objects
     */
    public function fetch(array $params) : array
    {
        $this->items = [];

        $this->log->debug("Fetching items from API");

        $params = array_merge($this->apiDefaults, $params);
        $response = $this->source->setOptions($this->apiEndpoint, $params)->fetch();
        $this->processResponse($response, $params);

        $this->log->debug(count($this->items)." items fetched from API");

        return $this->items;
    }

    /**
     * Collection of Item objects
     *
     * @return array
     */
    public function transfer() : array
    {
        return $this->items;
    }

    /**
     * Create the item's stat objects from JSON data
     *
     * @param  array $item
     * @return array
     */
    private function createStats(array $item) : array
    {
        $stats = [];

        foreach ($item['stats'] as $key => $value) {
            $stats[] = new Stat($key, $value);
        }

        return $stats;
    }

    /**
     * Convert response data into Item objects
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

            foreach ($response['data'] as $item) {
                $item['version'] = $params['version'];
                $item['region'] = $params['region'];
                $this->items[$item['id']] = $this->create($item);
            }
        }
    }
}
