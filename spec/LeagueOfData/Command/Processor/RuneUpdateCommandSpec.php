<?php
namespace spec\LeagueOfData\Command\Processor;

use PhpSpec\ObjectBehavior;

class RuneUpdateCommandSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\Processor\RuneUpdateCommand');
        $this->shouldHaveType('Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('update:rune');
    }
}
