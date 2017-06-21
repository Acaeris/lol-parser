<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Models\Champion\Champion;
use LeagueOfData\Models\Interfaces\ChampionInterface;
use LeagueOfData\Service\Interfaces\ChampionServiceInterface;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;
use LeagueOfData\Service\Interfaces\ChampionSpellsServiceInterface;
use LeagueOfData\Service\Interfaces\ChampionPassivesServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;

/**
 * Champion object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonChampions implements ChampionServiceInterface
{
    /**
     * @var AdapterInterface API adapter
     */
    private $adapter;

    /**
     * @var LoggerInterface logger
     */
    private $log;

    /**
     * @var ChampionStatsServiceInterface Champion Stat factory
     */
    private $statService;

    /**
     * @var ChampionSpellsServiceInterface Spell factory
     */
    private $spellService;

    /**
     * @var ChampionPassivesServiceInterface Passive factory
     */
    private $passiveService;

    /**
     * @var array Champion Objects
     */
    private $champions;

    /**
     * Setup champion factory service
     *
     * @param AdapterInterface                 $adapter
     * @param LoggerInterface                  $log
     * @param ChampionStatsServiceInterface    $statService
     * @param ChampionSpellsServiceInterface   $spellService
     * @param ChampionPassivesServiceInterface $passiveService
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log,
        ChampionStatsServiceInterface $statService, ChampionSpellsServiceInterface $spellService,
        ChampionPassivesServiceInterface $passiveService)
    {
        $this->adapter = $adapter;
        $this->log = $log;
        $this->statService = $statService;
        $this->spellService = $spellService;
        $this->passiveService = $passiveService;
    }

    /**
     * Add champion objects to internal array
     *
     * @param array $champions Champion objects
     */
    public function add(array $champions)
    {
        foreach ($champions as $champion) {
            $this->champions[$champion->getChampionID()] = $champion;
        }
    }

    /**
     * Create the champion object from array data
     *
     * @param array $champion
     * @return Champion
     */
    public function create(array $champion) : ChampionInterface
    {
        $spells = [];

        foreach ($champion['spells'] as $id => $spell) {
            $spell['number'] = $id;
            $spell['id'] = $champion['id'];
            $spell['version'] = $champion['version'];
            $spell['region'] = $champion['region'];
            $spells[] = $this->spellService->create($spell);
        }

        return new Champion(
            $champion['id'],
            $champion['name'],
            $champion['title'],
            $champion['partype'],
            $champion['tags'],
            $this->statService->create($champion),
            $this->passiveService->create($champion),
            $spells,
            preg_replace('/\\.[^.\\s]{3,4}$/', '', $champion['image']['full']),
            $champion['version'],
            $champion['region']
        );
    }

    /**
     * Fetch Champions
     *
     * @param RequestInterface $request
     * @return array Champion Objects
     */
    public function fetch(RequestInterface $request) : array
    {
        $this->log->debug("Fetching champions from API");
        $response = $this->adapter->fetch($request);
        $this->champions = [];
        if (count($response) > 0) {
            $this->processResponse($response, $request);
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
     * Convert response data into Champion objects
     *
     * @param array            $response
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

            foreach ($response['data'] as $champion) {
                $champion['version'] = $params['version'];
                $champion['region'] = $params['region'];
                $this->champions[$champion['id']] = $this->create($champion);
            }
        }
    }
}
