<?php

namespace spec\LeagueOfData\Models\Champion;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Models\Interfaces\ChampionSpellVarsInterface;
use LeagueOfData\Models\Interfaces\ChampionSpellResourceInterface;

class ChampionSpellSpec extends ObjectBehavior
{
    public function let(ChampionSpellVarsInterface $variables, ChampionSpellResourceInterface $resource)
    {
        $this->beConstructedWith(
            1, // Champion ID
            "Disintegrate", // Spell Name
            "Q", // Spell control key
            "Disintegrate", // Icon image filename
            5, // Max rank
            "Test Description", // Description
            "Test Tooltip", // Tooltip
            [4, 5, 6, 7, 8], // Cooldowns
            [625, 630, 635, 640, 645], // Ranges
            [null, [0, 1, 2, 3, 4]], // Effects
            [$variables], // Variables
            $resource, // Resource
            '7.9.1', // Version
            'euw' // Region
        );
    }

    public function it_is_initializable_and_immutable()
    {
        $this->shouldHaveType('LeagueOfData\Models\Champion\ChampionSpell');
        $this->shouldImplement('LeagueOfData\Models\Interfaces\ChampionSpellInterface');
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_required_data(ChampionSpellResourceInterface $resource)
    {
        $this->getChampionID()->shouldReturn(1);
        $this->getSpellName()->shouldReturn("Disintegrate");
        $this->getKey()->shouldReturn("Q");
        $this->getImageName()->shouldReturn("Disintegrate");
        $this->getMaxRank()->shouldReturn(5);
        $this->getDescription()->shouldReturn("Test Description");
        $this->getTooltip()->shouldReturn("Test Tooltip");
        $this->getCooldowns()->shouldReturn([4, 5, 6, 7, 8]);
        $this->getRanges()->shouldReturn([625, 630, 635, 640, 645]);
        $this->getEffects()->shouldReturn([null, [0, 1, 2, 3, 4]]);
        $this->getVars()->shouldReturnArrayOfSpellVars();
        $this->getResource()->shouldReturn($resource);
        $this->getVersion()->shouldReturn('7.9.1');
        $this->getRegion()->shouldReturn('euw');
    }

    public function it_can_fetch_cooldown_by_rank()
    {
        $this->getCooldownByRank(3)->shouldReturn(6);
    }

    public function it_can_fetch_range_by_rank()
    {
        $this->getRangeByRank(3)->shouldReturn(635);
    }

    public function it_can_get_effect_by_key()
    {
        $this->getEffectByKey(1)->shouldReturn([0, 1, 2, 3, 4]);
    }

    public function it_can_get_an_effect_value_by_key_and_rank()
    {
        $this->getEffectValue(1, 3)->shouldReturn(2.0);
    }

    public function it_should_throw_exception_if_effect_not_available()
    {
        $this->shouldThrow('InvalidArgumentException')->during('getEffectByKey', [2]);
    }

    public function it_should_throw_exception_if_rank_too_high()
    {
        $this->shouldThrow('InvalidArgumentException')->during('getCooldownByRank', [6]);
        $this->shouldThrow('InvalidArgumentException')->during('getRangeByRank', [6]);
        $this->shouldThrow('InvalidArgumentException')->during('getEffectValue', [1, 6]);
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfSpellVars' => function($spellVars) {
                foreach ($spellVars as $variable) {
                    if (!$variable instanceof ChampionSpellVarsInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
