<?php

namespace spec\App\Models\Champions;

use PhpSpec\ObjectBehavior;

class ChampionStatsSpec extends ObjectBehavior
{
    public function it_has_all_core_parts()
    {
        $armor = (float) 1;
        $armorPerLevel = (float) 2;
        $health = (float) 3;
        $healthPerLevel = (float) 4;
        $healthRegen = (float) 5;
        $healthRegenPerLevel = (float) 6;
        $mana = (float) 7;
        $manaPerLevel = (float) 8;
        $manaRegen = (float) 9;
        $manaRegenPerLevel = (float) 10;
        $attackDamage = (float) 11;
        $attackDamagePerLevel = (float) 12;
        $attackSpeedOffset = (float) 13;
        $attackSpeedPerLevel = (float) 14;
        $attackRange = (float) 15;
        $spellBlock = (float) 16;
        $spellBlockPerLevel = (float) 17;
        $movespeed = (float) 18;
        $crit = (float) 19;
        $critPerLevel = (float) 20;

        $this->beConstructedWith(
            $health,
            $healthPerLevel,
            $healthRegen,
            $healthRegenPerLevel,
            $mana,
            $manaPerLevel,
            $manaRegen,
            $manaRegenPerLevel,
            $attackDamage,
            $attackDamagePerLevel,
            $attackSpeedOffset,
            $attackSpeedPerLevel,
            $attackRange,
            $armor,
            $armorPerLevel,
            $spellBlock,
            $spellBlockPerLevel,
            $movespeed,
            $crit,
            $critPerLevel
        );

        $this->getHealth()->shouldReturn($health);
        $this->getHealthPerLevel()->shouldReturn($healthPerLevel);
        $this->getHealthRegen()->shouldReturn($healthRegen);
        $this->getHealthRegenPerLevel()->shouldReturn($healthRegenPerLevel);
        $this->getMana()->shouldReturn($mana);
        $this->getManaPerLevel()->shouldReturn($manaPerLevel);
        $this->getManaRegen()->shouldReturn($manaRegen);
        $this->getManaRegenPerLevel()->shouldReturn($manaRegenPerLevel);
        $this->getAttackDamage()->shouldReturn($attackDamage);
        $this->getAttackDamagePerLevel()->shouldReturn($attackDamagePerLevel);
        $this->getAttackSpeedOffset()->shouldReturn($attackSpeedOffset);
        $this->getAttackSpeedPerLevel()->shouldReturn($attackSpeedPerLevel);
        $this->getAttackRange()->shouldReturn($attackRange);
        $this->getArmor()->shouldReturn($armor);
        $this->getArmorPerLevel()->shouldReturn($armorPerLevel);
        $this->getSpellBlock()->shouldReturn($spellBlock);
        $this->getSpellBlockPerLevel()->shouldReturn($spellBlockPerLevel);
        $this->getMovespeed()->shouldReturn($movespeed);
        $this->getCrit()->shouldReturn($crit);
        $this->getCritPerLevel()->shouldReturn($critPerLevel);
    }
}
