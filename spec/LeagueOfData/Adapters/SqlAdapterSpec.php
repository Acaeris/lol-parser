<?php

namespace spec\LeagueOfData\Adapters;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Adapters\RequestInterface;

class SqlAdapterSpec extends ObjectBehavior
{
    public function let(LoggerInterface $log, Connection $database)
    {
        $this->beConstructedWith($log, $database);
    }

    public function it_should_be_able_to_insert_records(RequestInterface $request, Connection $database)
    {
        $request->requestFormat('sql')->shouldBeCalled();
        $request->data()->willReturn(['Test']);
        $request->type()->willReturn('champions');
        $database->insert('champions', ['Test'])->willReturn(1);
        $this->insert($request)->shouldReturn(1);
    }

    public function it_should_be_able_to_update_records(RequestInterface $request, Connection $database)
    {
        $request->requestFormat('sql')->shouldBeCalled();
        $request->data()->willReturn(['Test']);
        $request->type()->willReturn('champions');
        $request->where()->willReturn(['region' => 'na','id' => 1]);
        $database->update('champions', ['Test'], ['region' => 'na', 'id' => 1])->willReturn(1);
        $this->update($request)->shouldReturn(1);
    }

    public function it_should_be_able_to_fetch_records(RequestInterface $request, Connection $database)
    {
        $request->requestFormat('sql')->shouldBeCalled();
        $request->query()->willReturn('SELECT * FROM champions WHERE region = :region AND id = :id');
        $request->where()->willReturn(['region' => 'na','id' => 1]);
        $database->fetchAll('SELECT * FROM champions WHERE region = :region AND id = :id', ['region' => 'na', 'id' => 1])
            ->willReturn(['Test']);
        $this->fetch($request)->shouldReturn(['Test']);
    }
}
