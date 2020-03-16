<?php

namespace App\Services\Sql\Item;

use App\Mappers\Items\ItemMapper;
use App\Models\Items\Item;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use App\Services\StoreServiceInterface;
use App\Models\Stat;

/**
 * Item object SQL factory.
 *
 * @package LeagueOfData\Services\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ItemCollection implements StoreServiceInterface
{
    /* @var Connection DB connection */
    private $dbConn;

    /* @var LoggerInterface Logger */
    private $log;

    /* @var ItemMapper Item object mapper */
    private $itemMapper;

    /* @var array Version objects */
    private $items = [];

    /**
     * Setup item factory service
     *
     * @param Connection $connection
     * @param LoggerInterface $log
     * @param ItemMapper $itemMapper
     */
    public function __construct(
        Connection $connection,
        LoggerInterface $log,
        ItemMapper $itemMapper
    ) {
        $this->dbConn = $connection;
        $this->log = $log;
        $this->itemMapper = $itemMapper;
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
     * Clear the internal collection
     */
    public function clear()
    {
        $this->items = [];
    }

    /**
     * Store the item objects in the DB
     */
    public function store()
    {
        $this->log->debug("Storing ".count($this->items)." new/updated items");

        foreach ($this->items as $item) {
            $select = "SELECT item_id FROM items WHERE item_id = :item_id AND version = :version";

            $this->storeStats($item);

            if ($this->dbConn->fetchAll($select, $item->getKeyData())) {
                $this->dbConn->update('items', $this->convertItemToArray($item), $item->getKeyData());
                continue;
            }

            $this->dbConn->insert('items', $this->convertItemToArray($item));
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
     * @param string $query SQL Query
     * @param array  $where SQL Where parameters
     * @return array Item Objects
     */
    public function fetch(string $query, array $where = []): array
    {
        $this->log->debug("Fetching items from DB");

        $this->items = [];
        $this->processResults($this->dbConn->fetchAll($query, $where));

        $this->log->debug(count($this->items)." items fetched from DB");

        return $this->items;
    }

    /**
     * Fetch the stats for the given item
     *
     * @param array $item
     * @return array
     */
    private function fetchStats(array $item) : array
    {
        $select = "SELECT * FROM item_stats WHERE item_id = :item_id AND version = :version AND region = :region";
        $where = ['item_id' => $item['item_id'], 'version' => $item['version'], 'region' => $item['region']];
        $stats = [];
        $results = $this->dbConn->fetchAll($select, $where);

        if ($results !== false) {
            foreach ($results as $stat) {
                $stats[] = new Stat($stat['stat_name'], $stat['stat_value']);
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
        foreach ($item->getStats() as $stat) {
            $select = "SELECT item_id FROM item_stats WHERE item_id = :item_id AND version = :version "
                . "AND stat_name = :stat_name AND region = :region";
            $where = $item->getKeyData();
            $where['stat_name'] = $stat->getStatName();
            $data = array_merge($where, ['stat_value' => $stat->getStatModifier()]);

            if ($this->dbConn->fetchAll($select, $where)) {
                $this->dbConn->update('item_stats', $data, $where);
                continue;
            }

            $this->dbConn->insert('item_stats', $data);
        }
    }

    /**
     * Converts Item object into SQL data array
     *
     * @param Item $item
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
            foreach ($results as $item) {
                $item['stats'] = $this->fetchStats($item);
                $this->items[$item['item_id']] = $this->itemMapper->mapFromArray($item);
            }
        }
    }
}
