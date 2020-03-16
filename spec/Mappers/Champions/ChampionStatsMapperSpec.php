<?php

namespace spec\App\Mappers\Champions;

use PhpSpec\ObjectBehavior;

class ChampionStatsMapperSpec extends ObjectBehavior
{
    private $mockData = [
        "attackrange" => 150,
        "mpperlevel" => 45,
        "mp" => 105.6,
        "attackdamage" => 60.376,
        "hp" => 537.8,
        "hpperlevel" => 85,
        "attackdamageperlevel" => 3.2,
        "armor" => 24.384,
        "mpregenperlevel" => 0,
        "hpregen" => 6.59,
        "critperlevel" => 0,
        "spellblockperlevel" => 1.25,
        "mpregen" => 0,
        "attackspeedperlevel" => 3,
        "spellblock" => 32.1,
        "movespeed" => 345,
        "attackspeedoffset" => -0.04,
        "crit" => 0,
        "hpregenperlevel" => 0.5,
        "armorperlevel" => 3.8
    ];

    public function it_can_map_data_from_array()
    {
        $this->mapFromArray($this->mockData)
            ->shouldReturnAnInstanceOf('App\Models\Champions\ChampionStats');
    }
}
