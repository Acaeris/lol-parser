<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\Interfaces\ChampionAttackInterface;
use LeagueOfData\Models\Interfaces\ChampionDefenseInterface;
use LeagueOfData\Models\ChampionResource;

class ChampionStatsSpec extends ObjectBehavior
{
    function let(
        ChampionAttackInterface $attack,
        ChampionDefenseInterface $armor,
        ChampionDefenseInterface $magicResist
    ) {
        $health = new ChampionResource(ChampionResource::RESOURCE_HEALTH, 100, 5, 10, 1);
        $mana = new ChampionResource(ChampionResource::RESOURCE_MANA, 100, 5, 10, 1);
        $this->beConstructedWith($health, $mana, $attack, $armor, $magicResist, 250);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\ChampionStats');
    }

    function it_has_a_movement_speed()
    {
        $this->moveSpeed()->shouldReturn(250);
    }
}
