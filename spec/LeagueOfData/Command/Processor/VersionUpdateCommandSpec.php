<?php
namespace spec\LeagueOfData\Command\Processor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use LeagueOfData\Service\FetchServiceInterface;
use LeagueOfData\Service\StoreServiceInterface;
use LeagueOfData\Service\MessageQueueInterface;
use LeagueOfData\Entity\Version\VersionInterface;

class VersionUpdateCommandSpec extends ObjectBehavior
{
    public function let(InputInterface $input)
    {
        $input->bind(Argument::cetera())->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->validate()->willReturn();
        $input->hasArgument('command')->willReturn(false);
        $input->getOption('force')->willReturn(false);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Command\Processor\VersionUpdateCommand');
        $this->shouldHaveType('Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('update:version');
    }

    public function it_updates_the_version_data(
        ContainerInterface $container,
        InputInterface $input,
        OutputInterface $output,
        FetchServiceInterface $versionApi,
        StoreServiceInterface $versionDb,
        LoggerInterface $logger,
        MessageQueueInterface $rabbitMq,
        VersionInterface $mockVersion
    ) {
        $container->get('version-db')->willReturn($versionDb);
        $container->get('version-api')->willReturn($versionApi);
        $container->get('logger')->willReturn($logger);
        $container->get('rabbitmq')->willReturn($rabbitMq);

        $versionDb->fetch('SELECT * FROM versions', [])->willReturn([]);
        $versionDb->clear()->shouldBeCalled();
        $versionDb->add([$mockVersion])->shouldBeCalled();
        $versionDb->store()->shouldBeCalled();
        $versionApi->fetch(Argument::any())->willReturn([$mockVersion]);
        $versionApi->transfer()->willReturn([$mockVersion]);

        $mockVersion->getFullVersion()->willReturn('7.9.1');

        $rabbitMq->addProcessToQueue('update:champion', Argument::cetera())->shouldBeCalled();
        $rabbitMq->addProcessToQueue('update:item', Argument::cetera())->shouldBeCalled();
        $rabbitMq->addProcessToQueue('update:rune', Argument::cetera())->shouldBeCalled();

        $this->setContainer($container);
        $this->run($input, $output);
    }
}
