<?php

namespace LeagueOfData\Models\Json;

use LeagueOfData\Models\MatchHistories;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Models\Json\JsonMatchHistory;
use LeagueOfData\Adapters\API\MatchListRequest;

class JsonMatchHistories implements MatchHistories
{
    private $source;

    public function __construct(AdapterInterface $adapter)
    {
        $this->source = $adapter;
    }

    public function add($id, $region) {
        return new JsonMatchHistory(json_decode("{"
            . "'matchId': {$id},"
            . "'region': {$region}"
            . "}"));
    }

    public function findBySummoner($id) {
        $request = new MatchListRequest();
        $response = $this->source->fetch($request->prepare(['id' => $id, 'region' => 'euw']));
        $matches = [];
        foreach ($response->matches as $match) {
            $matches[] = new JsonMatchHistory($match);
        }
        return $matches;
    }
}
