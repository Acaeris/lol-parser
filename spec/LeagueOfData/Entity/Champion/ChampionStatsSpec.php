<?php

namespace spec\LeagueOfData\Entity\Champion;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Entity\Champion\ChampionAttackInterface;
use LeagueOfData\Entity\Champion\ChampionDefenseInterface;
use LeagueOfData\Entity\Champion\ChampionRegenResourceInterface;

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
        $this->shouldHaveType('LeagueOfData\Entity\Champion\ChampionStats');
        $this->shouldImplement('LeagueOfData\Entity\Champion\ChampionStatsInterface');
        $this->shouldImplement('LeagueOfData\Entity\EntityInterface');
    }

    public function it_has_all_data_available()
    {
        $this->getChampionID()->shouldReturn(1);
        $this->getMoveSpeed()->shouldReturn(335.0);
        $this->getHealth()->shouldReturnAnInstanceOf('\LeagueOfData\Entity\Champion\ChampionRegenResourceInterface');
        $this->getResource()
            ->shouldReturnAnInstanceOf('\LeagueOfData\Entity\Champion\ChampionRegenResourceInterface');
        $this->getAttack()->shouldReturnAnInstanceOf('\LeagueOfData\Entity\Champion\ChampionAttackInterface');
        $this->getArmor()->shouldReturnAnInstanceOf('\LeagueOfData\Entity\Champion\ChampionDefenseInterface');
        $this->getMagicResist()->shouldReturnAnInstanceOf('\LeagueOfData\Entity\Champion\ChampionDefenseInterface');
        $this->getVersion()->shouldReturn('7.8.1');
        $this->getRegion()->shouldReturn('euw');
    }
}
