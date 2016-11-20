<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\API\MatchListRequest;
use LeagueOfData\Service\Interfaces\MatchHistoryService;
use LeagueOfData\Models\MatchHistory;

class JsonMatchHistories implements MatchHistoryService
{
    private $source;

    public function __construct(AdapterInterface $adapter)
    {
        $this->source = $adapter;
    }

    public function add($id, $region) {
        return new MatchHistory(json_decode("{"
            . "'matchId': {$id},"
            . "'region': {$region}"
            . "}"));
    }

    public function find($id) {
        $request = new MatchListRequest();
        $response = $this->source->fetch($request->prepare(['id' => $id, 'region' => 'euw']));
        $matches = [];
        foreach ($response->matches as $match) {
            $matches[] = new MatchHistory($match);
        }
        return $matches;
    }
}
