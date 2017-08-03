<?php

namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Repository\Match\SqlMatchPlayerRepository;
use LeagueOfData\Repository\MatchList\JsonMatchListRepository;
use LeagueOfData\Entity\Match\MatchInterface;

class MatchListUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        LoggerInterface $logger,
        JsonMatchListRepository $apiRepository,
        SqlMatchPlayerRepository $dbRepository
    ) {
        $this->beConstructedWith($logger, $apiRepository, $dbRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\MatchListUpdateCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_has_been_configured()
    {
        $this->getName()->shouldReturn('update:matchlist');
        $this->getDescription()->shouldReturn('API processor command for match list data');
    }

    public function it_updates_the_match_list_data(
        InputInterface $input,
        OutputInterface $output,
        JsonMatchListRepository $apiRepository,
        SqlMatchPlayerRepository $dbRepository,
        MatchInterface $mockMatch
    ) {
        $dbRepository->fetch(new AnyValuesToken)->willReturn([]);
        $apiRepository->fetch(new AnyValuesToken)->willReturn([$mockMatch]);

        $dbRepository->clear()->shouldBeCalled();
        $dbRepository->add([$mockMatch])->shouldBeCalled();
        $dbRepository->store()->shouldBeCalled();

        $this->run($input, $output);
    }
}
