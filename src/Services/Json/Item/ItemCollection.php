<?php

namespace App\Services\Json\Item;

use App\Models\Items\Item;
use Psr\Log\LoggerInterface;
use App\Services\FetchServiceInterface;
use App\Models\EntityInterface;
use App\Models\Stat;
use App\Adapters\AdapterInterface;

/**
 * Item object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ItemCollection implements FetchServiceInterface
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
     * Setup item factory service
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
     * @param array $item
     * @return EntityInterface
     */
    public function create(array $item) : EntityInterface
    {
        return new Item(
            $item['id'],
            (isset($item['name']) ? $item['name'] : ''),
            (isset($item['description']) ? $item['description'] : ''),
            (isset($item['gold']['total']) ? $item['gold']['total'] : ''),
            (isset($item['gold']['sell']) ? $item['gold']['sell'] : ''),
            $this->createStats($item),
            $item['version'],
            $item['region']
        );
    }

    /**
     * Fetch Items
     *
     * @param string[] $params API parameters
     * @return Item[] Item Objects
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
     * @return Item[]
     */
    public function transfer() : array
    {
        return $this->items;
    }

    /**
     * Create the item's stat objects from JSON data
     *
     * @param array $item
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
