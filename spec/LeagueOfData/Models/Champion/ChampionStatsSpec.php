<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\Interfaces\ChampionAttackInterface;
use LeagueOfData\Models\Interfaces\ChampionDefenseInterface;
use LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface;

class ChampionStatsSpec extends ObjectBehavior
{
    public function let(
        ChampionRegenResourceInterface $health,
        ChampionRegenResourceInterface $mana,
        ChampionAttackInterface $attack,
        ChampionDefenseInterface $armor,
        ChampionDefenseInterface $magicResist
    ) {
        $this->beConstructedWith(1, $health, $mana, $attack, $armor, $magicResist, 335, '7.8.1', 'euw');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionStats');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionStatsInterface');
    }

    public function it_has_a_champion_id()
    {
        $this->getID()->shouldReturn(1);
    }

    public function it_has_a_movement_speed()
    {
        $this->moveSpeed()->shouldReturn(335.0);
    }

    public function it_has_health()
    {
        $this->health()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface');
    }

    public function it_has_resource()
    {
        $this->resource()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface');
    }

    public function it_has_attack_data()
    {
        $this->attack()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionAttackInterface');
    }

    public function it_has_armor()
    {
        $this->armor()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionDefenseInterface');
    }

    public function it_has_magic_resist()
    {
        $this->magicResist()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionDefenseInterface');
    }

    public function it_has_a_version()
    {
        $this->version()->shouldReturn('7.8.1');
    }

    public function it_has_a_region()
    {
        $this->region()->shouldReturn('euw');
    }
}
