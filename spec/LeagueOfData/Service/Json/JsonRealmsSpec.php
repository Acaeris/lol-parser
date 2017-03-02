<?php

namespace spec\LeagueOfData\Service\Json;

use PhpSpec\ObjectBehavior;

use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\RealmRequest;
use LeagueOfData\Models\Realm;
use Psr\Log\LoggerInterface;

class JsonRealmsSpec extends ObjectBehavior
{
    function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $request = new RealmRequest(['region' => 'euw']);
        $adapter->fetch($request)->willReturn([
            'cdn' => 'http://ddragon.leagueoflegends.com/cdn',
            'v' => '7.4.3'
        ]);
        $request = new RealmRequest(['region' => 'eune']);
        $adapter->fetch($request)->willReturn([
            'cdn' => 'http://ddragon.leagueoflegends.com/cdn',
            'v' => '7.4.3'
        ]);
        $request = new RealmRequest(['region' => 'na']);
        $adapter->fetch($request)->willReturn([
            'cdn' => 'http://ddragon.leagueoflegends.com/cdn',
            'v' => '7.4.3'
        ]);
        $this->beConstructedWith($adapter, $logger);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\JsonRealms');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\RealmService');
    }

    function it_should_find_all_realm_data()
    {
        $this->findAll()->shouldReturnArrayOfRealms();
    }

    function getMatchers()
    {
        return [
            'returnArrayOfRealms' => function($realms) {
                foreach ($realms as $realm) {
                    if (!$realm instanceof Realm) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
