<?php

namespace LeagueOfData\Service\Sql;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use LeagueOfData\Adapters\Request\ChampionStatsRequest;
use LeagueOfData\Service\Interfaces\ChampionServiceInterface;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;
use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Models\Interfaces\ChampionInterface;

/**
 * Champion object SQL factory.
 * @package LeagueOfData\Service\Sql
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class SqlChampions implements ChampionServiceInterface
{
    /* @var AdapterInterface DB adapter */
    private $dbAdapter;
    /* @var LoggerInterface Logger */
    private $log;
    /* @var JsonChampionStats Champion Stat factory */
    private $statService;
    /* @var array Champion objects */
    private $champions = [];

    /**
     * Setup champion factory service
     *
     * @param AdapterInterface $adapter
     * @param LoggerInterface  $log
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log,
        ChampionStatsServiceInterface $statBuilder)
    {
        $this->dbAdapter = $adapter;
        $this->log = $log;
        $this->statService = $statBuilder;
    }

    /**
     * Add champion objects to internal array
     *
     * @param array $champions Champion objects
     */
    public function add(array $champions)
    {
        foreach ($champions as $champion) {
            if ($champion instanceof ChampionInterface) {
                $this->champions[$champion->getChampionID()] = $champion;
                continue;
            }
            $this->log->error('Incorrect object supplied to Champion service', [$champion]);
        }
    }

    /**
     * Fetch Champions
     *
     * @param RequestInterface $request
     * @return array Champion Objects
     */
    public function fetch(RequestInterface $request) : array
    {
        $this->log->debug("Fetching champions from DB");
        $results = $this->dbAdapter->fetch($request);
        $this->champions = [];
        $this->processResults($results);
        $this->log->debug(count($this->champions)." champions fetched from DB");

        return $this->champions;
    }

    /**
     * Store the champion objects in the database
     */
    public function store()
    {
        $this->log->debug("Storing ".count($this->champions)." new/updated champions");

        foreach ($this->champions as $champion) {
            $request = new ChampionRequest(
                ['champion_id' => $champion->getChampionID(), 'version' => $champion->getVersion()],
                'champion_name',
                $this->convertChampionToArray($champion)
            );

            $this->statService->add([$champion->getStats()]);

            if ($this->dbAdapter->fetch($request)) {
                $this->dbAdapter->update($request);

                continue;
            }
            $this->dbAdapter->insert($request);
        }

        $this->statService->store();
    }

    /**
     * Get collection of champions for transfer to a different process.
     *
     * @return array Champion objects
     */
    public function transfer() : array
    {
        return $this->champions;
    }

    /**
     * Create the champion object from array data
     *
     * @param array $champion
     *
     * @return ChampionInterface
     */
    public function create(array $champion) : ChampionInterface
    {
        $request = new ChampionStatsRequest([
            'version' => $champion['version'],
            'region' => $champion['region'],
            'champion_id' => $champion['champion_id'],
        ], '*');
        $stats = $this->statService->fetch($request);
        return new Champion(
            $champion['champion_id'],
            $champion['champion_name'],
            $champion['title'],
            $champion['resource_type'],
            explode('|', $champion['tags']),
            $stats[$champion['champion_id']],
            $champion['image_name'],
            $champion['version'],
            $champion['region']
        );
    }

    /**
     * Converts Champion object into SQL data array
     *
     * @param ChampionInterface $champion
     *
     * @return array
     */
    private function convertChampionToArray(ChampionInterface $champion) : array
    {
        return [
            'champion_id' => $champion->getChampionID(),
            'champion_name' => $champion->getName(),
            'title' => $champion->getTitle(),
            'resource_type' => $champion->getResourceType(),
            'tags' => $champion->getTagsAsString(),
            'image_name' => $champion->getImageName(),
            'version' => $champion->getVersion(),
            'region' => $champion->getRegion()
        ];
    }

    /**
     * Convert result data into Champion objects
     *
     * @param array $results
     */
    private function processResults(array $results)
    {
        if ($results !== false) {
            if (!is_array($results)) {
                $results = [ $results ];
            }

            foreach ($results as $champion) {
                $this->champions[$champion['champion_id']] = $this->create($champion);
            }
        }
    }
}
