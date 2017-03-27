<?php

namespace spec\LeagueOfData\Service\Json;

use PhpSpec\ObjectBehavior;

use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\RealmRequest;
use LeagueOfData\Models\Realm;
use Psr\Log\LoggerInterface;

class JsonRealmsSpec extends ObjectBehavior
{
    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $request = new RealmRequest(['region' => 'euw']);
        $adapter->fetch($request)->willReturn((object) [
            'cdn' => 'http://ddragon.leagueoflegends.com/cdn',
            'v' => '7.4.3'
        ]);
        $request = new RealmRequest(['region' => 'eune']);
        $adapter->fetch($request)->willReturn((object) [
            'cdn' => 'http://ddragon.leagueoflegends.com/cdn',
            'v' => '7.4.3'
        ]);
        $request = new RealmRequest(['region' => 'na']);
        $adapter->fetch($request)->willReturn((object) [
            'cdn' => 'http://ddragon.leagueoflegends.com/cdn',
            'v' => '7.4.3'
        ]);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\JsonRealms');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\RealmServiceInterface');
    }

    public function it_should_find_all_realm_data()
    {
        $this->findAll()->shouldReturnArrayOfRealms();
    }

    public function getMatchers()
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
