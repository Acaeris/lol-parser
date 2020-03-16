<?php

namespace spec\App\Models\Champions;

use PhpSpec\ObjectBehavior;
use App\Models\Champions\ChampionBlockItem;

class ChampionBlockSpec extends ObjectBehavior
{
    public function it_has_all_core_parts(
        ChampionBlockItem $item
    ) {
        $items = [$item];
        $recMath = true;
        $type = 'Type';

        $this->beConstructedWith($items, $type, $recMath);

        $this->getItems()->shouldReturn($items);
        $this->getType()->shouldReturn($type);
        $this->isRecMath()->shouldReturn($recMath);
    }
}
