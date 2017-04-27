<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Service\Interfaces\ItemServiceInterface;
use LeagueOfData\Adapters\Request\ItemRequest;
use LeagueOfData\Models\Item\Item;
use LeagueOfData\Models\Item\ItemStat;
use LeagueOfData\Adapters\AdapterInterface;
use Psr\Log\LoggerInterface;

/**
 * Item object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonItems implements ItemServiceInterface
{
    /* @var AdapterInterface API adapter */
    private $source;
    /* @var LoggerInterface logger */
    private $log;
    /* @var array Item Objects */
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
     * Add an item to the collection
     *
     * @param Item $item
     */
    public function add(Item $item)
    {
        $this->items[$item->getID()] = $item;
    }

    /**
     * Add all item objects to internal array
     *
     * @param array $items Item objects
     */
    public function addAll(array $items)
    {
        foreach ($items as $item) {
            $this->items[$item->getID()] = $item;
        }
    }

    /**
     * Fetch Items
     *
     * @param string $version
     * @param int    $itemId
     * @param string $region
     *
     * @return array Item Objects
     */
    public function fetch(string $version, int $itemId = null, string $region = 'euw') : array
    {
        $this->log->debug("Fetching items from API for version: {$version}".(
            isset($itemId) ? " [{$itemId}]" : ""
        ));

        $params = [ 'version' => $version, 'region' => $region ];

        if (isset($itemId) && !empty($itemId)) {
            $params['id'] = $itemId;
        }

        $request = new ItemRequest($params);
        $response = $this->source->fetch($request);
        $this->items = [];
        if (count($response) > 0) {
            $this->processResponse($response, $version, $region);
        }
        $this->log->debug(count($this->items)." items fetched from API");

        return $this->items;
    }

    /**
     * Not implemented in JSON API calls
     */
    public function store()
    {
        throw new \Exception("Request to store data through JSON API not available.");
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
     * Create the item object from JSON data
     *
     * @param array $item
     * @param string $version
     *
     * @return Item
     */
    private function create(array $item) : Item
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
     * Create the item stats objects from JSON data
     *
     * @param array $item
     *
     * @return array
     */
    private function createStats(array $item) : array
    {
        $stats = [];

        foreach ($item['stats'] as $key => $value) {
            $stats[] = new ItemStat($key, $value);
        }

        return $stats;
    }

    /**
     * Convert response data into Item objects
     *
     * @param array  $response
     * @param string $version
     * @param string $region
     */
    private function processResponse(array $response, string $version, string $region)
    {
        if ($response !== false) {
            if (!isset($response['data'])) {
                $temp['data'] = [ $response ];
                $response = $temp;
            }

            foreach ($response['data'] as $item) {
                $item['version'] = $version;
                $item['region'] = $region;
                $this->items[$item['id']] = $this->create($item);
            }
        }
    }
}
