<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Service\Interfaces\ChampionServiceInterface;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\ChampionRequest;
use Psr\Log\LoggerInterface;

/**
 * Champion object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonChampions implements ChampionServiceInterface
{
    /* @var AdapterInterface API adapter */
    private $source;
    /* @var LoggerInterface logger */
    private $log;
    /* @var JsonChampionStats Champion Stat factory */
    private $statService;
    /* @var array Champion Objects */
    private $champions;

    /**
     * Setup champion factory service
     *
     * @param AdapterInterface $adapter
     * @param LoggerInterface  $log
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log,
        ChampionStatsServiceInterface $statService)
    {
        $this->source = $adapter;
        $this->log = $log;
        $this->statService = $statService;
    }

    /**
     * Add a champion to the collection
     *
     * @param Champion $champion
     */
    public function add(Champion $champion)
    {
        $this->champions[$champion->getID()] = $champion;
    }

    /**
     * Add all champion objects to internal array
     *
     * @param array $champions Champion objects
     */
    public function addAll(array $champions)
    {
        foreach ($champions as $champion) {
            $this->champions[$champion->getID()] = $champion;
        }
    }

    /**
     * Fetch Champions
     *
     * @param string $version
     * @param int    $championId
     * @param string $region
     *
     * @return array Champion Objects
     */
    public function fetch(string $version, int $championId = null, string $region = 'euw') : array
    {
        $this->log->debug("Fetching champions from API for version: {$version}".(
            isset($championId) ? " [{$championId}]" : ""
        ));

        $params = ['version' => $version, 'region' => $region];

        if (isset($championId) && !empty($championId)) {
            $params['id'] = $championId;
        }

        $request = new ChampionRequest($params);
        $response = $this->source->fetch($request);
        $this->champions = [];
        if (count($response) > 0) {
            $this->processResponse($response, $version, $region);
        }
        $this->log->debug(count($this->champions)." champions fetched from API");

        return $this->champions;
    }

    /**
     * Not implemented in JSON API calls
     */
    public function store()
    {
        throw new \Exception("Request to store data through JSON API not available.");
    }

    /**
     * Collection of Champion objects
     *
     * @return array
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
     * @return Champion
     */
    public function create(array $champion) : Champion
    {
        return new Champion(
            $champion['id'],
            $champion['name'],
            $champion['title'],
            $champion['partype'],
            $champion['tags'],
            $this->statService->create($champion),
            $champion['version'],
            $champion['region']
        );
    }

    /**
     * Convert response data into Champion objects
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

            foreach ($response['data'] as $champion) {
                $champion['version'] = $version;
                $champion['region'] = $region;
                $this->champions[$champion['id']] = $this->create($champion);
            }
        }
    }
}
