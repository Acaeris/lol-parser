<?php

namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Repository\Summoner\JsonSummonerRepository;
use LeagueOfData\Repository\Summoner\SqlSummonerRepository;
use LeagueOfData\Entity\Summoner\SummonerInterface;

class SummonerUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        InputInterface $input,
        LoggerInterface $logger,
        JsonSummonerRepository $apiRepository,
        SqlSummonerRepository $dbRepository
    ) {
        $input->bind(new AnyValuesToken)->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
        $input->hasArgument('command')->willReturn(false);
        $input->getOption('region')->willReturn('euw');
        $input->getOption('summonerId')->willReturn(1);
        $input->getOption('summonerName')->willReturn();
        $input->getOption('accountId')->willReturn();
        $input->getOption('force')->willReturn(false);
        $this->beConstructedWith($logger, $apiRepository, $dbRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\SummonerUpdateCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_has_been_configured()
    {
        $this->getName()->shouldReturn('update:summoner');
        $this->getDescription()->shouldReturn('API processor command for summoner data');
    }

    public function it_updates_the_summoner(
        InputInterface $input,
        OutputInterface $output,
        JsonSummonerRepository $apiRepository,
        SqlSummonerRepository $dbRepository,
        SummonerInterface $mockSummoner
    ) {
        $dbRepository->fetch(new AnyValuesToken)->willReturn([]);
        $apiRepository->fetch(new AnyValuesToken)->willReturn([$mockSummoner]);

        $dbRepository->clear()->shouldBeCalled();
        $dbRepository->add([$mockSummoner])->shouldBeCalled();
        $dbRepository->store()->shouldBeCalled();

        $this->run($input, $output);
    }
}
