<?php

namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValueToken;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Service\Json\Item\ItemCollection as ApiCollection;
use LeagueOfData\Service\Sql\Item\ItemCollection as DbCollection;
use LeagueOfData\Entity\Item\ItemInterface;

class ItemUpdateCommandSpec extends ObjectBehavior
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
        $input->getOption('itemId')->willReturn();

        $this->beConstructedWith($logger, $apiAdapter, $dbAdapter);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\ItemUpdateCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('update:item');
    }

    public function it_updates_the_item_data(
        InputInterface $input,
        OutputInterface $output,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter,
        ItemInterface $mockItem
    ) {

        $dbAdapter->fetch('SELECT * FROM items WHERE version = :version AND region = :region',
            ["version" => "7.9.1", "region" => "euw"])->willReturn([]);
        $dbAdapter->clear()->shouldBeCalled();
        $dbAdapter->add([$mockItem])->shouldBeCalled();
        $dbAdapter->store()->shouldBeCalled();
        $apiAdapter->fetch(new AnyValueToken)->willReturn([$mockItem]);
        $apiAdapter->transfer()->willReturn([$mockItem]);

        $this->run($input, $output);
    }
}
