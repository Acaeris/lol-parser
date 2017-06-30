<?php

namespace LeagueOfData\Service\Json\Champion;

use Psr\Log\LoggerInterface;
use LeagueOfData\Entity\Champion\Champion;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Service\FetchServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;

/**
 * Champion object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class ChampionCollection implements FetchServiceInterface
{
    /**
     * @var array Default parameters for API query
     */
    private $apiDefaults = [ 'region' => 'euw', 'champData' => 'all', 'champListData' => 'all' ];

    /**
     * @var string API Endpoint
     */
    private $apiEndpoint = 'static-data/v3/champions';

    /**
     * @var AdapterInterface API adapter
     */
    private $adapter;

    /**
     * @var LoggerInterface logger
     */
    private $log;

    /**
     * @var ChampionStatsCollection Champion Stat factory
     */
    private $statService;

    /**
     * @var ChampionSpellCollection Spell factory
     */
    private $spellService;

    /**
     * @var ChampionPassiveCollection Passive factory
     */
    private $passiveService;

    /**
     * @var array Champion Objects
     */
    private $champions;

    /**
     * Setup champion factory service
     *
     * @param AdapterInterface          $adapter
     * @param LoggerInterface           $log
     * @param ChampionStatsCollection   $statService
     * @param ChampionSpellCollection   $spellService
     * @param ChampionPassiveCollection $passiveService
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log, ChampionStatsCollection $statService,
        ChampionSpellCollection $spellService, ChampionPassiveCollection $passiveService)
    {
        $this->adapter = $adapter;
        $this->log = $log;
        $this->statService = $statService;
        $this->spellService = $spellService;
        $this->passiveService = $passiveService;
    }

    /**
     * Create the champion object from array data
     *
     * @param array $champion
     * @return EntityInterface
     */
    public function create(array $champion) : EntityInterface
    {
        return new Champion(
            $champion['id'],
            $champion['name'],
            $champion['title'],
            $champion['partype'],
            $champion['tags'],
            $this->statService->create($champion),
            $this->passiveService->create($champion),
            $this->fetchSpells($champion),
            preg_replace('/\\.[^.\\s]{3,4}$/', '', $champion['image']['full']),
            $champion['version'],
            $champion['region']
        );
    }

    /**
     * Fetch Champions
     *
     * @param array Fetch parameters
     * @return array Champion Objects
     */
    public function fetch(array $params) : array
    {
        $this->champions = [];
        $this->log->debug("Fetching champions from API");

        $query = $this->apiEndpoint
            . (isset($params['champion_id']) ? '/' . $params['champion_id'] : '');
        unset($params['champion_id']);
        $params = array_merge($this->apiDefaults, $params);
        $response = $this->adapter->setOptions($query, $params)->fetch();
        $this->processResponse($response, $params);

        $this->log->debug(count($this->champions)." champions fetched from API");

        return $this->champions;
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
     * Fetch spells for object creation
     *
     * @param array $champion
     * @return array
     */
    private function fetchSpells(array $champion) : array
    {
        $spells = [];

        foreach ($champion['spells'] as $id => $spell) {
            $spell['number'] = $id;
            $spell['id'] = $champion['id'];
            $spell['version'] = $champion['version'];
            $spell['region'] = $champion['region'];
            $spells[] = $this->spellService->create($spell);
        }

        return $spells;
    }

    /**
     * Convert response data into Champion objects
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

            foreach ($response['data'] as $champion) {
                $champion['version'] = $params['version'];
                $champion['region'] = $params['region'];
                $this->champions[$champion['id']] = $this->create($champion);
            }
        }
    }
}
