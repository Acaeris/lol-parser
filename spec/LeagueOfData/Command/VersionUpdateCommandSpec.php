<?php
namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Entity\Version\VersionInterface;
use LeagueOfData\Service\Json\Version\VersionCollection as ApiCollection;
use LeagueOfData\Service\Sql\Version\VersionCollection as DbCollection;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class VersionUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        InputInterface $input,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter,
        LoggerInterface $logger,
        ProducerInterface $producer
    ) {
        $input->bind(Argument::cetera())->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
        $input->hasArgument('command')->willReturn(false);
        $input->getOption('force')->willReturn(false);

        $this->beConstructedWith($logger, $apiAdapter, $dbAdapter, $producer, $producer, $producer);
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
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter,
        VersionInterface $mockVersion
    ) {

        $dbAdapter->fetch('SELECT * FROM versions', [])->willReturn([]);
        $dbAdapter->clear()->shouldBeCalled();
        $dbAdapter->add([$mockVersion])->shouldBeCalled();
        $dbAdapter->store()->shouldBeCalled();
        $apiAdapter->fetch(Argument::any())->willReturn([$mockVersion]);
        $apiAdapter->transfer()->willReturn([$mockVersion]);

        $mockVersion->getFullVersion()->willReturn('7.9.1');

        $this->run($input, $output);
    }
}
