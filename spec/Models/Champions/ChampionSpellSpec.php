<?php

namespace spec\App\Models\Champions;

use PhpSpec\ObjectBehavior;
use App\Models\Image;
use App\Models\Spells\SpellLevelTip;
use App\Models\Spells\SpellVar;

class ChampionSpellSpec extends ObjectBehavior
{
    public function it_has_all_core_parts(
        Image $image,
        SpellLevelTip $spellLevelTip,
        SpellVar $spellVar
    ) {
        $key = 'Disintigrate';
        $spellName = 'Disintigrate';
        $description = 'Test Description';
        $sanitisedDescription = 'Sanitised description';
        $tooltip = 'Test tooltip';
        $sanitisedTooltip = 'Sanitised tooltip';
        $resource = 'Mana';
        $costs = [1, 2, 3];
        $costBurn = '1/2/3';
        $costType = 'Mana';
        $cooldowns = [1, 3, 5];
        $cooldownBurn = '1/3/5';
        $altImages = [$image];
        $spellVars = [$spellVar];
        $effects = [null, [1, 2, 3, 4, 5]];
        $effectBurn = ['', '1/2/3/4/5'];
        $ranges = [2, 4, 8];
        $rangeBurn = '2/4/8';
        $maxRank = 5;

        $this->beConstructedWith(
            $key,
            $spellName,
            $description,
            $sanitisedDescription,
            $tooltip,
            $sanitisedTooltip,
            $resource,
            $costs,
            $costType,
            $cooldowns,
            $image,
            $altImages,
            $spellLevelTip,
            $spellVars,
            $effects,
            $ranges,
            $maxRank
        );

        $this->getKey()->shouldReturn($key);
        $this->getName()->shouldReturn($spellName);
        $this->getDescription()->shouldReturn($description);
        $this->getSanitisedDescription()->shouldReturn($sanitisedDescription);
        $this->getTooltip()->shouldReturn($tooltip);
        $this->getSanitisedTooltip()->shouldReturn($sanitisedTooltip);
        $this->getResource()->shouldReturn($resource);
        $this->getCosts()->shouldReturn($costs);
        $this->getCostBurn()->shouldReturn($costBurn);
        $this->getCostType()->shouldReturn($costType);
        $this->getCooldowns()->shouldReturn($cooldowns);
        $this->getCooldownBurn()->shouldReturn($cooldownBurn);
        $this->getImage()->shouldReturn($image);
        $this->getAltImages()->shouldReturn($altImages);
        $this->getSpellLevelTip()->shouldReturn($spellLevelTip);
        $this->getSpellVars()->shouldReturn($spellVars);
        $this->getEffects()->shouldReturn($effects);
        $this->getEffectBurn()->shouldReturn($effectBurn);
        $this->getRanges()->shouldReturn($ranges);
        $this->getRangeBurn()->shouldReturn($rangeBurn);
        $this->getMaxRank()->shouldReturn($maxRank);
    }
}
