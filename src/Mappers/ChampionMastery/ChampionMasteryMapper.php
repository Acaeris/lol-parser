<?php

namespace App\Mappers\ChampionMastery;

use App\Models\ChampionMastery\ChampionMastery;
use Psr\Http\Message\ResponseInterface;

class ChampionMasteryMapper
{
    /**
     * Map data from the Riot API response to objects
     * @param ResponseInterface $response
     * @return ChampionMastery[] Collection of Masteries
     */
    public function mapFromApiData(ResponseInterface $response): array
    {
        $data = $response->getBody();

        $results = [];

        foreach ($data as $mastery) {
            $results[] = new ChampionMastery(
                $mastery['championId'],
                $mastery['summonerId'],
                $mastery['championLevel'],
                $mastery['chestGranted'],
                $mastery['championPoints'],
                $mastery['championPointsSinceLastLevel'],
                $mastery['championPointsUntilNextLevel'],
                $mastery['tokensEarned'],
                $mastery['lastPlayTime']
            );
        }

        return $results;
    }
}
