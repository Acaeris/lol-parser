<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\Interfaces\ChampionAttackInterface;
use LeagueOfData\Models\Interfaces\ChampionDefenseInterface;
use LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface;

class ChampionStatsSpec extends ObjectBehavior
{
    function let(
        ChampionRegenResourceInterface $health,
        ChampionRegenResourceInterface $mana,
        ChampionAttackInterface $attack,
        ChampionDefenseInterface $armor,
        ChampionDefenseInterface $magicResist
    ) {
        $this->beConstructedWith($health, $mana, $attack, $armor, $magicResist, 335);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionStats');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionStatsInterface');
    }

    function it_has_a_movement_speed()
    {
        $this->moveSpeed()->shouldReturn(335.0);
    }

    function it_has_health()
    {
        $this->health()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface');
    }

    function it_has_resource()
    {
        $this->resource()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface');
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
