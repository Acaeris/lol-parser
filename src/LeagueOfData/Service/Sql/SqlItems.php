<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\Interfaces\ItemServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Models\Item\Item;
use LeagueOfData\Models\Item\ItemStat;
use LeagueOfData\Adapters\Request\ItemRequest;
use LeagueOfData\Adapters\Request\ItemStatsRequest;

/**
 * Item object SQL factory.
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class SqlItems implements ItemServiceInterface
{
    /* @var LeagueOfData\Adapters\AdapterInterface DB adapter */
    private $dbAdapter;
    /* @var Psr\Log\LoggerInterface Logger */
    private $log;
    /* @var array Version objects */
    private $items = [];

    /**
     * Setup item factory service
     *
     * @param AdapterInterface $adapter
     * @param LoggerInterface  $log
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->dbAdapter = $adapter;
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
            $this->items[$item->getID()] = $item;
        }
    }

    /**
     * Store the item objects in the DB
     */
    public function store()
    {
        $this->log->debug("Storing ".count($this->items)." new/updated items");

        foreach ($this->items as $item) {
            $request = new ItemRequest(
                [ 'item_id' => $item->getID(), 'version' => $item->version() ],
                'item_id',
                $this->convertItemToArray($item)
            );

            $this->storeStats($item);

            if ($this->dbAdapter->fetch($request)) {
                $this->dbAdapter->update($request);
                continue;
            }

            $this->dbAdapter->insert($request);
        }
    }

    /**
     * Transfer objects out to another service
     *
     * @return array Item objects
     */
    public function transfer() : array
    {
        return $this->items;
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
    public function fetch(string $version, int $itemId = null, string $region = 'euw'): array
    {
        $this->log->debug("Fetching items from DB for version: {$version}".(
            isset($itemId) ? " [{$itemId}]" : ""
        ));

        $where = [ 'version' => $version, 'region' => $region ];

        if (isset($itemId) && !empty($itemId)) {
            $where['item_id'] = $itemId;
        }
        
        $request = new ItemRequest($where, '*');
        $this->items = [];
        $results = $this->dbAdapter->fetch($request);

        if ($results !== false) {
            if (!is_array($results)) {
                $results = [ $results ];
            }

            foreach ($results as $item) {
                $item['region'] = $region;
                $this->items[$item['item_id']] = $this->create(
                    $item,
                    $this->fetchStats($item)
                );
            }
        }
        $this->log->debug(count($this->items)." items fetched from DB");

        return $this->items;
    }

    /**
     * Fetch the stats for the given item
     *
     * @param array $item
     *
     * @return array
     */
    private function fetchStats(array $item) : array
    {
        $request = new ItemStatsRequest(
            ['item_id' => $item['item_id'], 'version' => $item['version'], 'region' => $item['region']],
            '*'
        );
        $stats = [];
        $results = $this->dbAdapter->fetch($request);

        if ($results !== false) {
            foreach ($results as $stat) {
                $stats[] = new ItemStat($stat['stat_name'], $stat['stat_value']);
            }
        }

        return $stats;
    }

    /**
     * Store the item stats in the database
     *
     * @param Item $item
     */
    private function storeStats(Item $item)
    {
        foreach ($item->stats() as $key => $value) {
            $request = new ItemStatsRequest(
                ['item_id' => $item->getID(), 'version' => $item->version(), 'stat_name' => $key],
                'item_id',
                [
                    'item_id' => $item->getID(),
                    'stat_name' => $key,
                    'stat_value' => $value,
                    'version' => $item->version(),
                    'region' => $item->region()
                ]
            );

            if ($this->dbAdapter->fetch($request)) {
                $this->dbAdapter->update($request);

                return;
            }
            $this->dbAdapter->insert($request);
        }
    }

    /**
     * Build the Item object from the given data.
     *
     * @param array $item
     * @param array $stats
     * @return Item
     */
    private function create(array $item, array $stats) : Item
    {
        return new Item(
            $item['item_id'],
            $item['item_name'],
            $item['description'],
            $item['purchase_value'],
            $item['sale_value'],
            $stats,
            $item['version'],
            $item['region']
        );
    }

    /**
     * Converts Item object into SQL data array
     *
     * @param Item $item
     *
     * @return array
     */
    private function convertItemToArray(Item $item) : array
    {
        return [
            'item_id' => $item->getID(),
            'item_name' => $item->name(),
            'description' => $item->description(),
            'purchase_value' => $item->goldToBuy(),
            'sale_value' => $item->goldFromSale(),
            'version' => $item->version(),
            'region' => $item->region()
        ];
    }
}
