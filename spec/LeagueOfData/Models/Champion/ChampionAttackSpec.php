<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;

class ChampionAttackSpec extends ObjectBehavior
{
    public function let()
    {
        $range = 575;
        $damage = 50.41;
        $damagePerLevel = 2.625;
        $attackSpeed = 0.08;
        $attackSpeedPerLevel = 1.36;
        // Note: No champion currently has base crit or crit per level.
        // Implemented to support the values in future.
        $critChance = 15;
        $critPerLevel = 1;
        $this->beConstructedWith($range, $damage, $damagePerLevel, $attackSpeed,
            $attackSpeedPerLevel, $critChance, $critPerLevel);
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

    public function it_can_output_an_attack_range()
    {
        $this->range()->shouldReturn(575.0);
    }

    public function it_can_output_base_attack_damage()
    {
        $this->baseDamage()->shouldReturn(50.41);
    }

    public function it_can_output_attack_damage_per_level()
    {
        $this->damagePerLevel()->shouldReturn(2.625);
    }

    public function it_can_output_attack_damage_at_a_given_level()
    {
        $this->damageAtLevel(5)->shouldReturn(60.91);
    }

    public function it_can_output_the_attack_speed()
    {
        $this->attackSpeed()->shouldReturn(0.08);
    }

    public function it_can_output_attack_speed_increase_per_level()
    {
        $this->attackSpeedPerLevel()->shouldReturn(1.36);
    }

    public function it_can_output_the_attack_speed_at_a_given_level()
    {
        $this->attackSpeedAtLevel(5)->shouldReturn(5.52);
    }

    public function it_can_output_the_base_crit_chance()
    {
        $this->baseCritChance()->shouldReturn(15.0);
    }

    public function it_can_output_the_crit_chance_increase_per_level()
    {
        $this->critChancePerLevel()->shouldReturn(1.0);
    }

    public function it_can_output_the_crit_chance_at_a_given_level()
    {
        $this->critChanceAtLevel(5)->shouldReturn(19.0);
    }
}
