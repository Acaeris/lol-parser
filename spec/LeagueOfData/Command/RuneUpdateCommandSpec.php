<?php
namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValueToken;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Service\Json\Rune\RuneCollection as ApiCollection;
use LeagueOfData\Service\Sql\Rune\RuneCollection as DbCollection;
use LeagueOfData\Entity\Rune\RuneInterface;

class RuneUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        InputInterface $input,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter,
        LoggerInterface $logger
    ) {
        $input->bind(new AnyValuesToken)->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
        $input->hasArgument('command')->willReturn(false);
        $input->getArgument('release')->willReturn('7.9.1');
        $input->getOption('force')->willReturn(false);
        $input->getOption('region')->willReturn('euw');
        $input->getOption('runeId')->willReturn();

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

    public function it_updates_the_rune_data(
        InputInterface $input,
        OutputInterface $output,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter,
        RuneInterface $mockRune
    ) {

        $dbAdapter->fetch('SELECT * FROM runes WHERE version = :version AND region = :region',
            ["version" => "7.9.1", "region" => "euw"])->willReturn([]);
        $dbAdapter->clear()->shouldBeCalled();
        $dbAdapter->add([$mockRune])->shouldBeCalled();
        $dbAdapter->store()->shouldBeCalled();
        $apiAdapter->fetch(new AnyValueToken)->willReturn([$mockRune]);
        $apiAdapter->transfer()->willReturn([$mockRune]);

        $this->run($input, $output);
    }
}
