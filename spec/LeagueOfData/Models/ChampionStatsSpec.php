<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\ChampionResource;
use LeagueOfData\Models\ChampionAttack;
use LeagueOfData\Models\ChampionDefense;

class ChampionStatsSpec extends ObjectBehavior
{
    function let()
    {
        $health = new ChampionResource(ChampionResource::RESOURCE_HEALTH, 100, 5, 10, 1);
        $mana = new ChampionResource(ChampionResource::RESOURCE_MANA, 100, 5, 10, 1);
        $attack = new ChampionAttack(100, 10, 1, 20, 2, 15, 1);
        $armor = new ChampionDefense(ChampionDefense::DEFENSE_ARMOR, 100, 1);
        $magicResist = new ChampionDefense(ChampionDefense::DEFENSE_MAGICRESIST, 100, 1);
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
