<?php

namespace LeagueOfData\Repository\Champion;

use Psr\Log\LoggerInterface;
use LeagueOfData\Entity\Champion\Champion;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Repository\FetchRepositoryInterface;
use LeagueOfData\Adapters\AdapterInterface;

/**
 * Champion object API repository.
 *
 * @package LeagueOfData\Repository
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
class JsonChampionRepository implements FetchRepositoryInterface
{
    /**
     * @var array Default parameters for API query
     */
    private $apiDefaults = [ 'region' => 'euw', 'tags' => 'all' ];

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
    private $logger;

    /**
     * @var JsonChampionStatsRepository Champion Stat repository
     */
    private $statRepository;

    /**
     * @var JsonChampionSpellRepository Spell repository
     */
    private $spellRepository;

    /**
     * @var JsonChampionPassiveRepository Passive repository
     */
    private $passiveRepository;

    /**
     * @var array Champion Objects
     */
    private $champions;

    /**
     * Setup Champion repository
     *
     * @param AdapterInterface          $adapter
     * @param LoggerInterface           $log
     * @param JsonChampionStatsRepository   $statRepository
     * @param JsonChampionSpellRepository   $spellRepository
     * @param JsonChampionPassiveRepository $passiveRepository
     */
    public function __construct(
        AdapterInterface $adapter,
        LoggerInterface $log,
        JsonChampionStatsRepository $statRepository,
        JsonChampionSpellRepository $spellRepository,
        JsonChampionPassiveRepository $passiveRepository
    ) {
        $this->adapter = $adapter;
        $this->logger = $log;
        $this->statRepository = $statRepository;
        $this->spellRepository = $spellRepository;
        $this->passiveRepository = $passiveRepository;
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
            $this->statRepository->create($champion),
            $this->passiveRepository->create($champion),
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
        $this->logger->debug("Fetching champions from API");

        $query = $this->apiEndpoint
            . (isset($params['champion_id']) ? '/' . $params['champion_id'] : '');
        unset($params['champion_id']);
        $params = array_merge($this->apiDefaults, $params);
        $response = $this->adapter->setOptions($query, $params)->fetch();
        $this->processResponse($response, $params);

        $this->logger->debug(count($this->champions)." champions fetched from API");

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
            $spells[] = $this->spellRepository->create($spell);
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
