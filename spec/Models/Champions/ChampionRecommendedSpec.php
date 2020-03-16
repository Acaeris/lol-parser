<?php

namespace spec\App\Models\Champions;

use PhpSpec\ObjectBehavior;
use App\Models\Champions\ChampionBlock;

class ChampionRecommendedSpec extends ObjectBehavior
{
    public function it_has_all_core_parts(
        ChampionBlock $block
    ) {
        $map = 'Summoner`s Rift';
        $championName = 'Annie';
        $title = 'Recommended';
        $mode = 'Core';
        $type = 'Type';
        $priority = true;
        $blocks = [$block];

        $this->beConstructedWith(
            $map,
            $championName,
            $title,
            $mode,
            $type,
            $priority,
            $blocks
        );

        $this->getMap()->shouldReturn($map);
        $this->getChampionName()->shouldReturn($championName);
        $this->getTitle()->shouldReturn($title);
        $this->getMode()->shouldReturn($mode);
        $this->getType()->shouldReturn($type);
        $this->isPriority()->shouldReturn($priority);
        $this->getBlocks()->shouldReturn($blocks);
    }
}
