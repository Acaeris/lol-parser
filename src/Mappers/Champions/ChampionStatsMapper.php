<?php

namespace App\Mappers\Champions;

use App\Models\Champions\ChampionStats;

class ChampionStatsMapper
{
    public function mapFromArray(array $data): ChampionStats
    {
        return new ChampionStats(
            $data['hp'],
            $data['hpperlevel'],
            $data['hpregen'],
            $data['hpregenperlevel'],
            $data['mp'],
            $data['mpperlevel'],
            $data['mpregen'],
            $data['mpregenperlevel'],
            $data['attackdamage'],
            $data['attackdamageperlevel'],
            $data['attackspeedoffset'],
            $data['attackspeedperlevel'],
            $data['attackrange'],
            $data['armor'],
            $data['armorperlevel'],
            $data['spellblock'],
            $data['spellblockperlevel'],
            $data['movespeed'],
            $data['crit'],
            $data['critperlevel']
        );
    }
}
