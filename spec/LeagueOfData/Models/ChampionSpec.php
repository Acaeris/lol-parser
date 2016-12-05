<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\ChampionStats;
use LeagueOfData\Models\ChampionResource;
use LeagueOfData\Models\ChampionAttack;
use LeagueOfData\Models\ChampionDefense;

class ChampionSpec extends ObjectBehavior
{
    function let()
    {
        $health = new ChampionResource(ChampionResource::RESOURCE_HEALTH, 100, 5, 10, 1);
        $mana = new ChampionResource(ChampionResource::RESOURCE_MANA, 100, 5, 10, 1);
        $attack = new ChampionAttack(100, 10, 1, 20, 2, 15, 1);
        $armor = new ChampionDefense(ChampionDefense::DEFENSE_ARMOR, 100, 1);
        $magicResist = new ChampionDefense(ChampionDefense::DEFENSE_MAGICRESIST, 100, 1);
        $stats = new ChampionStats($health, $mana, $attack, $armor, $magicResist, 250);
        $this->beConstructedWith(1, "Test", "Test Character", "mp", "Fighter|Mage", $stats, "6.21.1");
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion');
    }

    function it_has_an_id()
    {
        $this->id()->shouldReturn(1);
    }

    function it_has_a_name()
    {
        $this->name()->shouldReturn("Test");
    }

    function it_has_a_title()
    {
        $this->title()->shouldReturn("Test Character");
    }

    function it_has_a_client_version()
    {
        $this->version()->shouldReturn('6.21.1');
    }
}
