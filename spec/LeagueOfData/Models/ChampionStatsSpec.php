<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\Interfaces\ChampionAttackInterface;
use LeagueOfData\Models\Interfaces\ChampionDefenseInterface;
use LeagueOfData\Models\Interfaces\ChampionResourceInterface;

class ChampionStatsSpec extends ObjectBehavior
{
    function let(
        ChampionResourceInterface $health,
        ChampionResourceInterface $mana,
        ChampionAttackInterface $attack,
        ChampionDefenseInterface $armor,
        ChampionDefenseInterface $magicResist
    ) {
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

    function it_has_health()
    {
        $this->health()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionResourceInterface');
    }

    function it_has_resource()
    {
        $this->resource()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionResourceInterface');
    }

    function it_has_attack_data()
    {
        $this->attack()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionAttackInterface');
    }

    function it_has_armor()
    {
        $this->armor()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionDefenseInterface');
    }

    function it_has_magic_resist()
    {
        $this->magicResist()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionDefenseInterface');
    }
}
