<?php

namespace spec\LeagueOfData\Entity\Champion;

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

    public function it_is_initializable_and_immutable()
    {
        $this->shouldHaveType('LeagueOfData\Entity\Champion\ChampionAttack');
        $this->shouldImplement('LeagueOfData\Entity\Champion\ChampionAttackInterface');
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_required_data_available()
    {
        $this->getRange()->shouldReturn(575.0);
        $this->getBaseDamage()->shouldReturn(50.41);
        $this->getDamagePerLevel()->shouldReturn(2.625);
        $this->getAttackSpeed()->shouldReturn(0.08);
        $this->getAttackSpeedPerLevel()->shouldReturn(1.36);
        $this->getBaseCritChance()->shouldReturn(15.0);
        $this->getCritChancePerLevel()->shouldReturn(1.0);
    }

    public function it_can_calculate_attack_damage_at_a_given_level()
    {
        $this->calculateDamageAtLevel(5)->shouldReturn(60.91);
    }

    public function it_can_calculate_the_attack_speed_at_a_given_level()
    {
        $this->calculateAttackSpeedAtLevel(5)->shouldReturn(5.52);
    }

    public function it_can_calculate_the_crit_chance_at_a_given_level()
    {
        $this->calculateCritChanceAtLevel(5)->shouldReturn(19.0);
    }
}
