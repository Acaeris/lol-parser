<?php

namespace spec\App\Models\Champions;

use PhpSpec\ObjectBehavior;

class ChampionBlockItemSpec extends ObjectBehavior
{
    public function it_has_all_core_parts()
    {
        $itemId = 1;
        $count = 2;

        $this->beConstructedWith($itemId, $count);

        $this->getItemId()->shouldReturn($itemId);
        $this->getCount()->shouldReturn($count);
    }
}
