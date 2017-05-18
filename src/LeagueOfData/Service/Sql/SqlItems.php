<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Service\Interfaces\ItemServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Item\Item;
use LeagueOfData\Models\Item\ItemStat;
use LeagueOfData\Models\Interfaces\ItemInterface;
use LeagueOfData\Adapters\Request\ItemRequest;
use LeagueOfData\Adapters\Request\ItemStatsRequest;

/**
 * Item object SQL factory.
 *
 * @package LeagueOfData\Service|Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class SqlItems implements ItemServiceInterface
{
    /**
     * @var AdapterInterface DB adapter
     */
    private $dbAdapter;
    /**
     * @var LoggerInterface Logger
     */
    private $log;
    /**
     * @var array Version objects
     */
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
            $this->items[$item->getItemID()] = $item;
        }
    }

    /**
     * Build the Item object from the given data.
     *
     * @param array $item
     * @param array $stats
     * @return ItemInterface
     */
    public function create(array $item, array $stats) : ItemInterface
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
     * Store the item objects in the DB
     */
    public function store()
    {
        $this->log->debug("Storing ".count($this->items)." new/updated items");

        foreach ($this->items as $item) {
            $request = new ItemRequest(
                [ 'item_id' => $item->getID(), 'version' => $item->getVersion() ],
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
     * @param RequestInterface $request
     * @return array Item Objects
     */
    public function fetch(RequestInterface $request): array
    {
        $this->log->debug("Fetching items from DB");
        $results = $this->dbAdapter->fetch($request);
        $this->items = [];
        $this->processResults($results);
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
        foreach ($item->getStats() as $key => $value) {
            $request = new ItemStatsRequest(
                ['item_id' => $item->getItemID(), 'version' => $item->getVersion(), 'stat_name' => $key],
                'item_id',
                [
                    'item_id' => $item->getItemID(),
                    'stat_name' => $key,
                    'stat_value' => $value,
                    'version' => $item->getVersion(),
                    'region' => $item->getRegion()
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
     * Converts Item object into SQL data array
     *
     * @param Item $item
     *
     * @return array
     */
    private function convertItemToArray(Item $item) : array
    {
        return [
            'item_id' => $item->getItemID(),
            'item_name' => $item->getName(),
            'description' => $item->getDescription(),
            'purchase_value' => $item->getGoldToBuy(),
            'sale_value' => $item->getGoldFromSale(),
            'version' => $item->getVersion(),
            'region' => $item->getRegion()
        ];
    }

    /**
     * Convert result data into Item objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            if (!is_array($results)) {
                $results = [ $results ];
            }

            foreach ($results as $item) {
                $this->items[$item['item_id']] = $this->create(
                    $item,
                    $this->fetchStats($item)
                );
            }
        }
    }
}
