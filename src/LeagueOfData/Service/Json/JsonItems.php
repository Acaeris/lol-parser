<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Service\Interfaces\ItemServiceInterface;
use LeagueOfData\Adapters\Request\ItemRequest;
use LeagueOfData\Models\Item\Item;
use LeagueOfData\Adapters\AdapterInterface;
use Psr\Log\LoggerInterface;

final class JsonItems implements ItemServiceInterface
{
    /* @var AdapterInterface API adapter */
    private $source;
    /* @var LoggerInterface logger */
    private $log;
    /* @var array Item Objects */
    private $items;

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
        $this->items[] = $item;
    }

    /**
     * Fetch Items
     *
     * @param string $version
     * @param int    $itemId
     * @return array Item Objects
     */
    public function fetch(string $version, int $itemId = null) : array
    {
        $this->log->info("Fetching items for version {$version}".(isset($itemId) ? " [{$itemId}]" : ""));

        if (!empty($itemId)) {
            return $this->find($version, $itemId);
        }
        
        return $this->findAll($version);
    }

    /**
     * Find all Item data by version
     *
     * @param string $version Version number
     * @return array Item objects
     */
    public function findAll(string $version) : array
    {
        $request = new ItemRequest(['version' => $version]);
        $response = $this->source->fetch($request);
        $this->items = [];
        
        foreach ($response->data as $item) {
            $this->items[] = $this->create($item, $response->version);
        }
        return $this->items;
    }

    /**
     * Find a specific item
     *
     * @param string $version
     * @param int    $itemId
     * @return array Item objects
     */
    public function find(string $version, int $itemId) : array
    {
        $request = new ItemRequest(['id' => $itemId, 'region' => 'euw', 'version' => $version]);
        $response = $this->source->fetch($request);
        $this->items = [ $this->create($response, $version) ];
        return $this->items;
    }

    /**
     * Not implemented in JSON API calls
     */
    public function store()
    {
        $this->log->error("Request to store data through JSON API not available.");
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
     * Create the item object from array data
     *
     * @param array $item
     * @param string $version
     * @return Item
     */
    private function create(\stdClass $item, string $version) : Item
    {
        return new Item(
            $item->id,
            $item->name,
            $item->description,
            $item->gold->total,
            $item->gold->sell,
            $version
        );
    }
}
