<?php
namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use LeagueOfData\Service\Json\Rune\RuneCollection as ApiCollection;
use LeagueOfData\Service\Sql\Rune\RuneCollection as DbCollection;

class RuneUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        InputInterface $input,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter,
        LoggerInterface $logger
    ) {
        $input->bind(Argument::cetera())->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
        $input->hasArgument('command')->willReturn(false);
        $input->getOption('force')->willReturn(false);

        $this->beConstructedWith($logger, $apiAdapter, $dbAdapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\RuneUpdateCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('update:rune');
    }
}
