<?php

namespace spec\LeagueOfData\Repository\Realm;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\Realm\RealmInterface;

class JsonRealmRepositorySpec extends ObjectBehavior
{
    private $mockData = [
        'cdn' => 'http://ddragon.leagueoflegends.com/cdn',
        'v' => '7.4.3'
    ];

    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $adapter->fetch("static-data/v3/realms", ["region" => "euw"])->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Realm\JsonRealmRepository');
        $this->shouldImplement('LeagueOfData\Repository\FetchRepositoryInterface');
    }

    public function it_should_find_all_realm_data()
    {
        $this->fetch([])->shouldReturnArrayOfRealms();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfRealms' => function ($realms) {
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
