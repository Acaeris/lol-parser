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
        $this->beConstructedWith(
            1, // Champion ID
            $health, // Health object
            $mana, // Resource object
            $attack, // Attack object
            $armor, // Armor object
            $magicResist, // Magic Resist object
            335, // Move Speed
            '7.8.1', // Version
            'euw' // Region
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionStats');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionStatsInterface');
    }

    public function it_has_all_data_available()
    {
        $this->getID()->shouldReturn(1);
        $this->getMoveSpeed()->shouldReturn(335.0);
        $this->getHealth()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface');
        $this->getResource()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionRegenResourceInterface');
        $this->getAttack()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionAttackInterface');
        $this->getArmor()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionDefenseInterface');
        $this->getMagicResist()->shouldReturnAnInstanceOf('\LeagueOfData\Models\Interfaces\ChampionDefenseInterface');
        $this->getVersion()->shouldReturn('7.8.1');
        $this->getRegion()->shouldReturn('euw');
    }
}
