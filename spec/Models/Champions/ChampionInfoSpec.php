<?php

namespace spec\App\Models\Champions;

use PhpSpec\ObjectBehavior;

class ChampionInfoSpec extends ObjectBehavior
{
    public function it_has_all_core_parts()
    {
        $difficulty = 1;
        $attack = 2;
        $defense = 3;
        $magic = 4;

        $this->beConstructedWith($difficulty, $attack, $defense, $magic);

        $this->getDifficulty()->shouldReturn($difficulty);
        $this->getAttack()->shouldReturn($attack);
        $this->getDefense()->shouldReturn($defense);
        $this->getMagic()->shouldReturn($magic);
    }
}
