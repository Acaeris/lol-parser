<?php

namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValueToken;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Repository\Champion\JsonChampionRepository;
use LeagueOfData\Repository\Champion\SqlChampionRepository;
use LeagueOfData\Entity\Champion\ChampionInterface;

class ChampionUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        InputInterface $input,
        JsonChampionRepository $apiRepository,
        SqlChampionRepository $dbRepository,
        LoggerInterface $logger
    ) {
        $input->bind(new AnyValuesToken)->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
        $input->hasArgument('command')->willReturn(false);
        $input->getArgument('release')->willReturn('7.9.1');
        $input->getOption('force')->willReturn(false);
        $input->getOption('region')->willReturn('euw');
        $input->getOption('championId')->willReturn();

        $this->beConstructedWith($logger, $apiRepository, $dbRepository);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\ChampionUpdateCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_has_been_configured()
    {
        $this->getName()->shouldReturn('update:champion');
        $this->getDescription()->shouldReturn('API processor command for champion data');
    }

    public function it_updates_the_champion_data(
        InputInterface $input,
        OutputInterface $output,
        JsonChampionRepository $apiRepository,
        SqlChampionRepository $dbRepository,
        ChampionInterface $mockChampion
    ) {

        $dbRepository->fetch(new AnyValuesToken)->willReturn([]);
        $dbRepository->clear()->shouldBeCalled();
        $dbRepository->add([$mockChampion])->shouldBeCalled();
        $dbRepository->store()->shouldBeCalled();
        $apiRepository->fetch(new AnyValueToken)->willReturn([$mockChampion]);
        $apiRepository->transfer()->willReturn([$mockChampion]);

        $this->run($input, $output);
    }
}
