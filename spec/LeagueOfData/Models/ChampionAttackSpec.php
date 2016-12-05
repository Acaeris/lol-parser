<?php

namespace spec\LeagueOfData\Models;

use PhpSpec\ObjectBehavior;

class ChampionAttackSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(100, 10, 1, 20, 2, 15, 1);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Models\ChampionAttack');
    }

    function it_is_immutable()
    {
        $this->shouldHaveType('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    function it_can_output_an_attack_range()
    {
        $this->range()->shouldReturn(100);
    }

    function it_can_output_base_attack_damage()
    {
        $this->baseDamage()->shouldReturn(10);
    }

    function it_can_output_attack_damage_per_level()
    {
        $this->damagePerLevel()->shouldReturn(1);
    }

    function it_can_output_attack_damage_at_a_given_level()
    {
        $this->damageAtLevel(5)->shouldReturn(15);
    }

    function it_can_output_the_attack_speed()
    {
        $this->attackSpeed()->shouldReturn(20);
    }

    function it_can_output_attack_speed_increase_per_level()
    {
        $this->attackSpeedPerLevel()->shouldReturn(2);
    }

    function it_can_output_the_attack_speed_at_a_given_level()
    {
        $this->attackSpeedAtLevel(5)->shouldReturn(30);
    }

    function it_can_output_the_base_crit_chance()
    {
        $this->baseCritChance()->shouldReturn(15);
    }

    function it_can_output_the_crit_chance_increase_per_level()
    {
        $this->critChancePerLevel()->shouldReturn(1);
    }

    function it_can_output_the_crit_chance_at_a_given_level()
    {
        $this->critChanceAtLevel(5)->shouldReturn(20);
    }

    function it_can_be_converted_to_array_for_storage()
    {
        $this->toArray()->shouldReturn([
            'attackRange' => 100,
            'attackDamage' => 10,
            'attackDamagePerLevel' => 1,
            'attackSpeedOffset' => 20,
            'attackSpeedPerLevel' => 2,
            'crit' => 15,
            'critPerLevel' => 1
        ]);
    }
}
