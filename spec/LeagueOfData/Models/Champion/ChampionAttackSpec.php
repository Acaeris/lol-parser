<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;

class ChampionAttackSpec extends ObjectBehavior
{
    public function let()
    {
        // Note: No champion currently has base crit or crit per level.
        // Implemented to support the values in future.
        $this->beConstructedWith(
            575, // Range
            50.41, // Damage
            2.625, // Damage Per Level
            0.08, // Attack Speed
            1.36, // Attack Speed Per Level
            15, // Crit Chance
            1 // Crit Per Level
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionAttack');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionAttackInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_data_available()
    {
        $this->range()->shouldReturn(575.0);
        $this->baseDamage()->shouldReturn(50.41);
        $this->damagePerLevel()->shouldReturn(2.625);
        $this->attackSpeed()->shouldReturn(0.08);
        $this->baseCritChance()->shouldReturn(15.0);
        $this->critChancePerLevel()->shouldReturn(1.0);
    }

    public function it_can_calculate_attack_damage_at_a_given_level()
    {
        $this->damageAtLevel(5)->shouldReturn(60.91);
        $this->attackSpeedPerLevel()->shouldReturn(1.36);
    }

    public function it_can_calculate_the_attack_speed_at_a_given_level()
    {
        $this->attackSpeedAtLevel(5)->shouldReturn(5.52);
    }

    public function it_can_calculate_the_crit_chance_at_a_given_level()
    {
        $this->critChanceAtLevel(5)->shouldReturn(19.0);
    }
}
