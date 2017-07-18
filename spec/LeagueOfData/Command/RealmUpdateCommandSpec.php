<?php

namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValueToken;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Repository\Realm\JsonRealmRepository;
use LeagueOfData\Repository\Realm\SqlRealmRepository;
use LeagueOfData\Entity\Realm\RealmInterface;

class RealmUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        InputInterface $input,
        JsonRealmRepository $apiRepository,
        SqlRealmRepository $dbRepository,
        LoggerInterface $logger
    ) {
        $input->bind(new AnyValuesToken)->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
        $input->hasArgument('command')->willReturn(false);
        $input->getOption('force')->willReturn(false);

        $this->beConstructedWith($logger, $apiRepository, $dbRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\RealmUpdateCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('update:realm');
    }

    public function it_updates_the_realm_data(
        InputInterface $input,
        OutputInterface $output,
        JsonRealmRepository $apiRepository,
        SqlRealmRepository $dbRepository,
        RealmInterface $mockRealm
    ) {

        $dbRepository->fetch('SELECT * FROM realms', [])->willReturn([]);
        $dbRepository->add([$mockRealm])->shouldBeCalled();
        $dbRepository->store()->shouldBeCalled();
        $apiRepository->fetch(new AnyValueToken)->willReturn([$mockRealm]);
        $apiRepository->transfer()->willReturn([$mockRealm]);

        $this->run($input, $output);
    }
}
