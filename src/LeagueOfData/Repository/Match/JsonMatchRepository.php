<?php

namespace LeagueOfData\Repository\Match;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Repository\FetchRepositoryInterface;
use LeagueOfData\Entity\EntityInterface;
use LeagueOfData\Entity\Match\Match;
use LeagueOfData\Entity\Match\MatchPlayer;

class JsonMatchRepository implements FetchRepositoryInterface
{
    /**
     * @var array Default parameters for API query
     */
    private $apiDefaults = ['region' => 'euw'];

    /**
     * @var string API Endpoint
     */
    private $apiEndpoint = 'match/v3/matches';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var array
     */
    private $matches;

    public function __construct(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->adapter = $adapter;
        $this->logger = $logger;
    }

    /**
     * Create the match object from array data
     *
     * @param  array $match
     * @return EntityInterface
     */
    public function create(array $match): EntityInterface
    {
        $match['participants'] = $this->mergePlayerData($match);

        return new Match(
            $match['gameId'],
            $match['gameMode'],
            $match['gameType'],
            $match['mapId'],
            $match['gameDuration'],
            $this->buildPlayerObjects($match),
            $match['gameVersion'],
            $match['region'],
            $match['seasonId'] ?? -1
        );
    }

    /**
     * Fetch Matches
     *
     * @param  array Fetch parameters
     * @return array Match Objects
     */
    public function fetch(array $params): array
    {
        $this->matches = [];
        $this->logger->debug("Fetching match from API");

        $query = $this->apiEndpoint.'/'.$params['match_id'];
        $params = array_merge($this->apiDefaults, $params);
        $response = $this->adapter->setOptions($query, $params)->fetch();
        $this->processResponse($response, $params);

        $this->logger->debug(count($this->matches)." matches fetch from API");

        return $this->matches;
    }

    /**
     * Collection of Match objects
     *
     * @return array
     */
    public function transfer(): array
    {
        return $this->matches;
    }

    /**
     * Merge Participant and Participant Identities data
     *
     * @param array $match
     * @return array
     */
    public function mergePlayerData(array $match): array
    {
        $players = [];

        foreach ($match['participants'] as $participant) {
            $players[$participant['participantId']] = $participant;
        }

        foreach ($match['participantIdentities'] as $participant) {
            if (isset($participant['player'])) {
                $players[$participant['participantId']]['accountId'] = $participant['player']['accountId'];
            }
        }

        return $players;
    }

    /**
     * Build Player Objects
     *
     * @param array $match
     * @return array
     */
    public function buildPlayerObjects(array $match): array
    {
        $players = [];

        foreach ($match['participants'] as $participant) {
            $players[] = new MatchPlayer(
                $match['gameId'],
                $participant['participantId'] ?? 0,
                $participant['accountId'] ?? 0,
                $participant['championId'],
                $match['region']
            );
        }

        return $players;
    }

    /**
     * Convert response data into match objects
     *
     * @param array $response
     * @param array $params
     */
    private function processResponse(array $response, array $params)
    {
        if (count($response) > 0 && $response !== false) {
            $response['region'] = $params['region'];
            $this->matches[$response['gameId']] = $this->create($response);
        }
    }
}
