<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Service\Interfaces\SummonerService;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\API\SummonerRequest;
use LeagueOfData\Models\Summoner;

class JsonSummoners implements SummonerService
{
    private $source;

    public function __construct(AdapterInterface $adapter)
    {
        $this->source = $adapter;
    }

    /**
     * Returns a Summoner Object from raw values.
     *
     * @param int    $summonerID
     * @param string $name
     * @param int    $level
     * @param int    $iconId
     * @param string $revisionDate
     *
     * @return \LeagueOfData\Models\Json\JsonSummoner
     */
    public function add(int $summonerID, string $name, int $level, int $iconId, string $revisionDate) : Summoner
    {
        return new Summoner(json_decode("{"
            . "'id': {$summonerID},"
            . "'name': {$name},"
            . "'summonerLevel': {$level},"
            . "'profileIconId': {$iconId},"
            . "'revisionDate': {$revisionDate}"
            . "}"));
    }

    /**
     * Find by a list of ids
     *
     * @param int[] $ids
     *
     * @return Summoner[]
     */
    public function find(array $ids) : array
    {
        $splitIds = array_chunk($ids, 40);
        $players = [];
        $request = new SummonerRequest();
        foreach ($splitIds as $group) {
            $response = $this->source->fetch($request->prepare(['ids' => implode(',', $group), 'region' => 'euw' ]));
            foreach ($response as $player) {
                $players[] = new Summoner($player);
            }
        }
        return $players;
    }
}
