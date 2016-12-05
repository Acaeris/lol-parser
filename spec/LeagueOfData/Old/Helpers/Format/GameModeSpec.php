<?php

namespace spec\LeagueOfData\Helpers\Format;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GameModeSpec extends ObjectBehavior
{
    function it_formats_the_name_of_the_game_mode()
    {
        $this->format("CLASSIC")->shouldReturn("Classic");
    }
}
