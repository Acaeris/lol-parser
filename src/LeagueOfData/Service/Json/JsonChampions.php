<?php

namespace LeagueOfData\Service\Json;

use Psr\Log\LoggerInterface;
use LeagueOfData\Entity\Champion\Champion;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Service\FetchServiceInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;

/**
 * Champion object JSON factory.
 *
 * @package LeagueOfData\Service\Json
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class JsonChampions implements FetchServiceInterface
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
     * @var FetchServiceInterface Champion Stat factory
     */
    private $statService;

    /**
     * @var FetchServiceInterface Spell factory
     */
    private $spellService;

    /**
     * @var FetchServiceInterface Passive factory
     */
    private $passiveService;

    /**
     * @var array Champion Objects
     */
    private $champions;

    /**
     * Setup champion factory service
     *
     * @param AdapterInterface      $adapter
     * @param LoggerInterface       $log
     * @param FetchServiceInterface $statService
     * @param FetchServiceInterface $spellService
     * @param FetchServiceInterface $passiveService
     */
    public function __construct(AdapterInterface $adapter, LoggerInterface $log, FetchServiceInterface $statService,
        FetchServiceInterface $spellService, FetchServiceInterface $passiveService)
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
