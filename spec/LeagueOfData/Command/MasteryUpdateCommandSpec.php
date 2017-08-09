<?php
namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValueToken;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Repository\Mastery\JsonMasteryRepository;
use LeagueOfData\Repository\Mastery\SqlMasteryRepository;
use LeagueOfData\Entity\Mastery\MasteryInterface;

class MasteryUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        InputInterface $input,
        JsonMasteryRepository $apiRepository,
        SqlMasteryRepository $dbRepository,
        LoggerInterface $logger
    ) {
        $input->bind(new AnyValuesToken)->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
        $input->hasArgument('command')->willReturn(false);
        $input->getArgument('release')->willReturn('7.9.1');
        $input->getOption('force')->willReturn(false);
        $input->getOption('region')->willReturn('euw');
        $input->getOption('masteryId')->willReturn();

        $this->beConstructedWith($logger, $apiRepository, $dbRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\MasteryUpdateCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('update:mastery');
    }

    public function it_updates_the_mastery_data(
        InputInterface $input,
        OutputInterface $output,
        JsonMasteryRepository $apiRepository,
        SqlMasteryRepository $dbRepository,
        MasteryInterface $mockMastery
    ) {

        $dbRepository->fetch(
            'SELECT * FROM masteries WHERE version = :version AND region = :region',
            ["version" => "7.9.1", "region" => "euw"]
        )->willReturn([]);
        $dbRepository->clear()->shouldBeCalled();
        $dbRepository->add([$mockMastery])->shouldBeCalled();
        $dbRepository->store()->shouldBeCalled();
        $apiRepository->fetch(new AnyValueToken)->willReturn([$mockMastery]);
        $apiRepository->transfer()->willReturn([$mockMastery]);

        $this->run($input, $output);
    }
}
