<?php

namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValueToken;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Service\Json\Champion\ChampionCollection as ApiCollection;
use LeagueOfData\Service\Sql\Champion\ChampionCollection as DbCollection;
use LeagueOfData\Entity\Champion\ChampionInterface;

class RealmUpdateCommandSpec extends ObjectBehavior
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
        $input->getOption('championId')->willReturn();

        $this->beConstructedWith($logger, $apiAdapter, $dbAdapter);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\ChampionUpdateCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('update:champion');
    }

    public function it_updates_the_champion_data(
        InputInterface $input,
        OutputInterface $output,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter,
        ChampionInterface $mockChampion
    ) {

        $dbAdapter->fetch('SELECT * FROM champions WHERE version = :version AND region = :region',
            ["version" => "7.9.1", "region" => "euw"])->willReturn([]);
        $dbAdapter->clear()->shouldBeCalled();
        $dbAdapter->add([$mockChampion])->shouldBeCalled();
        $dbAdapter->store()->shouldBeCalled();
        $apiAdapter->fetch(new AnyValueToken)->willReturn([$mockChampion]);
        $apiAdapter->transfer()->willReturn([$mockChampion]);

        $this->run($input, $output);
    }
}
