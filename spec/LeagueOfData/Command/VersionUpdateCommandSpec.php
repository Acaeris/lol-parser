<?php
namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValueToken;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Entity\Version\VersionInterface;
use LeagueOfData\Repository\Version\JsonVersionRepository;
use LeagueOfData\Repository\Version\SqlVersionRepository;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class VersionUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        InputInterface $input,
        JsonVersionRepository $apiRepository,
        SqlVersionRepository $dbRepository,
        LoggerInterface $logger,
        ProducerInterface $producer
    ) {
        $input->bind(new AnyValuesToken)->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
        $input->hasArgument('command')->willReturn(false);
        $input->getOption('force')->willReturn(false);

        $this->beConstructedWith($logger, $apiRepository, $dbRepository, $producer, $producer, $producer, $producer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\VersionUpdateCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('update:version');
    }

    public function it_updates_the_version_data(
        InputInterface $input,
        OutputInterface $output,
        JsonVersionRepository $apiRepository,
        SqlVersionRepository $dbRepository,
        VersionInterface $mockVersion
    ) {

        $dbRepository->fetch(new AnyValuesToken)->willReturn([]);
        $dbRepository->clear()->shouldBeCalled();
        $dbRepository->add([$mockVersion])->shouldBeCalled();
        $dbRepository->store()->shouldBeCalled();
        $apiRepository->fetch(new AnyValueToken)->willReturn([$mockVersion]);
        $apiRepository->transfer()->willReturn([$mockVersion]);

        $mockVersion->getFullVersion()->willReturn('7.9.1');

        $this->run($input, $output);
    }
}
