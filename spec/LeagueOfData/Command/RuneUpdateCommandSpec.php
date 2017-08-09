<?php
namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValueToken;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Repository\Rune\JsonRuneRepository;
use LeagueOfData\Repository\Rune\SqlRuneRepository;
use LeagueOfData\Entity\Rune\RuneInterface;

class RuneUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        InputInterface $input,
        JsonRuneRepository $apiRepository,
        SqlRuneRepository $dbRepository,
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

        $this->beConstructedWith($logger, $apiRepository, $dbRepository);
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
        JsonRuneRepository $apiRepository,
        SqlRuneRepository $dbRepository,
        RuneInterface $mockRune
    ) {

        $dbRepository->fetch(
            'SELECT * FROM runes WHERE version = :version AND region = :region',
            ["version" => "7.9.1", "region" => "euw"]
        )->willReturn([]);
        $dbRepository->clear()->shouldBeCalled();
        $dbRepository->add([$mockRune])->shouldBeCalled();
        $dbRepository->store()->shouldBeCalled();
        $apiRepository->fetch(new AnyValueToken)->willReturn([$mockRune]);
        $apiRepository->transfer()->willReturn([$mockRune]);

        $this->run($input, $output);
    }
}
