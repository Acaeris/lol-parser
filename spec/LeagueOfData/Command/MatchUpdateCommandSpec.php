<?php

namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Repository\Match\SqlMatchRepository;
use LeagueOfData\Repository\Match\JsonMatchRepository;
use LeagueOfData\Entity\Match\MatchInterface;

class MatchUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        LoggerInterface $logger,
        JsonMatchRepository $apiRepository,
        SqlMatchRepository $dbRepository
    ) {
        $this->beConstructedWith($logger, $apiRepository, $dbRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\MatchUpdateCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_has_been_configured()
    {
        $this->getName()->shouldReturn('update:match');
        $this->getDescription()->shouldReturn('API processor command for match data');
    }

    public function it_updates_the_match(
        InputInterface $input,
        OutputInterface $output,
        SqlMatchRepository $dbRepository,
        JsonMatchRepository $apiRepository,
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
