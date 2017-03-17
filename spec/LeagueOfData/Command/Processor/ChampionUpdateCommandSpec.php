<?php

namespace spec\LeagueOfData\Command\Processor;

use PhpSpec\ObjectBehavior;

class ChampionUpdateCommandSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\Processor\ChampionUpdateCommand');
        $this->shouldHaveType('Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand');
    }
}
