<?php

namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValueToken;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Repository\Item\JsonItemRepository;
use LeagueOfData\Repository\Item\SqlItemRepository;
use LeagueOfData\Entity\Item\ItemInterface;

class ItemUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        InputInterface $input,
        JsonItemRepository $apiRepository,
        SqlItemRepository $dbRepository,
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

        $this->beConstructedWith($logger, $apiRepository, $dbRepository);
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
        JsonItemRepository $apiRepository,
        SqlItemRepository $dbRepository,
        ItemInterface $mockItem
    ) {

        $dbRepository->fetch(new AnyValuesToken)->willReturn([]);
        $dbRepository->clear()->shouldBeCalled();
        $dbRepository->add([$mockItem])->shouldBeCalled();
        $dbRepository->store()->shouldBeCalled();
        $apiRepository->fetch(new AnyValueToken)->willReturn([$mockItem]);
        $apiRepository->transfer()->willReturn([$mockItem]);

        $this->run($input, $output);
    }
}
