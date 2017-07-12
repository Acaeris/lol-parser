<?php

namespace spec\LeagueOfData\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Service\Sql\Match\MatchCollection as DbCollection;
use LeagueOfData\Service\Json\MatchList\MatchListCollection as ApiCollection;
use LeagueOfData\Entity\Match\MatchInterface;

class MatchListUpdateCommandSpec extends ObjectBehavior
{
    public function let(
        InputInterface $input,
        LoggerInterface $logger,
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter
    ) {
        $this->beConstructedWith($logger, $apiAdapter, $dbAdapter);
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
        ApiCollection $apiAdapter,
        DbCollection $dbAdapter,
        MatchInterface $mockMatch
    ) {
        $dbAdapter->fetch(new AnyValuesToken)->willReturn([]);
        $apiAdapter->fetch(new AnyValuesToken)->willReturn([$mockMatch]);

        $dbAdapter->clear()->shouldBeCalled();
        $dbAdapter->add([$mockMatch])->shouldBeCalled();
        $dbAdapter->store()->shouldBeCalled();

        $this->run($input, $output);
    }
}
