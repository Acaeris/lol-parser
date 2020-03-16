<?php

namespace spec\App\Models\Champions;

use PhpSpec\ObjectBehavior;

class ChampionSkinSpec extends ObjectBehavior
{
    public function it_has_all_core_parts()
    {
        $championId = 1;
        $number = 2;
        $skinName = 'Goth Annie';

        $this->beConstructedWith($championId, $number, $skinName);

        $this->getChampionId()->shouldReturn($championId);
        $this->getNumber()->shouldReturn($number);
        $this->getSkinName()->shouldReturn($skinName);
    }
}
