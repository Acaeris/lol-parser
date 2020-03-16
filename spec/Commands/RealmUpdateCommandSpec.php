<?php

namespace spec\App\Commands;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValueToken;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Services\Json\Realm\RealmCollection as ApiCollection;
use App\Services\Sql\Realm\RealmCollection as DbCollection;
use App\Models\Realm\RealmInterface;

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
        $input->getOption('force')->willReturn(false);

        $this->beConstructedWith($logger, $apiAdapter, $dbAdapter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('App\Commands\RealmUpdateCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('update:realm');
    }

    public function it_updates_the_realm_data(
        InputInterface $input,
        OutputInterface $output,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter,
        RealmInterface $mockRealm
    ) {

        $dbAdapter->fetch('SELECT * FROM realms', [])->willReturn([]);
        $dbAdapter->add([$mockRealm])->shouldBeCalled();
        $dbAdapter->store()->shouldBeCalled();
        $apiAdapter->fetch(new AnyValueToken)->willReturn([$mockRealm]);
        $apiAdapter->transfer()->willReturn([$mockRealm]);

        $this->run($input, $output);
    }
}
