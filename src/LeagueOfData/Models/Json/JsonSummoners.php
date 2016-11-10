<?php

namespace LeagueOfData\Models\Json;

use LeagueOfData\Models\Interfaces\Summoners;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\API\SummonerRequest;

class JsonSummoners implements Summoners
{
    private $source;

    public function __construct(AdapterInterface $adapter)
    {
        $this->source = $adapter;
    }

    /**
     * Returns a Summoner Object from raw values.
     * 
     * @param type $id
     * @param type $name
     * @param type $level
     * @param type $iconId
     * @param type $revisionDate
     * @return \LeagueOfData\Models\Json\JsonSummoner
     */
    public function add($id, $name, $level, $iconId, $revisionDate) {
        return new JsonSummoner(json_decode("{"
            . "'id': {$id},"
            . "'name': {$name},"
            . "'summonerLevel': {$level},"
            . "'profileIconId': {$iconId},"
            . "'revisionDate': {$revisionDate}
            }"));
    }

    public function find($ids) {
        $splitIds = array_chunk($ids, 40);
        $players = [];
        $request = new SummonerRequest();
        foreach ($splitIds as $group) {
            $response = $this->source->fetch($request->prepare(['ids' => implode(',', $group), 'region' => 'euw' ]));
            foreach ($response as $player) {
                $players[] = new JsonSummoner($player);
            }
        }
        return $players;
    }

}
