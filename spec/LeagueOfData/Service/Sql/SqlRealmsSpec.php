<?php

namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Interfaces\RealmInterface;

class SqlRealmsSpec extends ObjectBehavior
{
    private $mockData = [[
        'cdn' => 'http://ddragon.leagueoflegends.com/cdn',
        'version' => '7.4.3'
    ]];

    public function let(AdapterInterface $adapter, LoggerInterface $logger, RequestInterface $request)
    {
        $adapter->fetch($request)->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlRealms');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\RealmServiceInterface');
    }

    public function it_should_find_all_realm_data(RequestInterface $request)
    {
        $this->fetch($request)->shouldReturnArrayOfRealms();
    }

    public function it_can_convert_data_to_realm_object()
    {
        $this->create($this->mockData[0])->shouldImplement('LeagueOfData\Models\Interfaces\RealmInterface');
    }

    public function it_can_add_and_retrieve_spell_objects_from_collection(RealmInterface $realm)
    {
        $realm->getVersion()->willReturn('7.9.1');
        $this->add([$realm]);
        $this->transfer()->shouldReturnArrayOfRealms();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfRealms' => function($realms) {
                foreach ($realms as $realm) {
                    if (!$realm instanceof RealmInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
