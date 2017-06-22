<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\FetchServiceInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Item\Item;
use LeagueOfData\Entity\Item\ItemStat;
use LeagueOfData\Entity\Item\ItemInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;

/**
 * Item object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonItems implements FetchServiceInterface
{
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
     * Add item objects to internal array
     *
     * @param array $items Item objects
     */
    public function add(array $items)
    {
        foreach ($items as $item) {
            $this->items[$item->getItemID()] = $item;
        }
    }

    /**
     * Create the item object from JSON data
     *
     * @param array $item
     * @return ItemInterface
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
     * @param RequestInterface $request
     * @return array Item Objects
     */
    public function fetch(RequestInterface $request) : array
    {
        $this->log->debug("Fetching items from API");
        $response = $this->source->fetch($request);
        $this->items = [];
        if (count($response) > 0) {
            $this->processResponse($response, $request);
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
     * @param RequestInterface $request
     */
    private function processResponse(array $response, RequestInterface $request)
    {
        if ($response !== false) {
            if (!isset($response['data'])) {
                $temp['data'] = [ $response ];
                $response = $temp;
            }

            $params = $request->where();

            foreach ($response['data'] as $item) {
                $item['version'] = $params['version'];
                $item['region'] = $params['region'];
                $this->items[$item['id']] = $this->create($item, []);
            }
        }
    }
}
