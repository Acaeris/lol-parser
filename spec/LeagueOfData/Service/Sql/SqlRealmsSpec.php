<?php

namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\RealmRequest;
use LeagueOfData\Models\Realm;
use Psr\Log\LoggerInterface;

class SqlRealmsSpec extends ObjectBehavior
{
    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $request = new RealmRequest([], 'SELECT `cdn`, `region`, MAX(`version`) FROM realm GROUP BY `region`');
        $adapter->fetch($request)->willReturn([[
            'cdn' => 'http://ddragon.leagueoflegends.com/cdn',
            'version' => '7.4.3',
            'region' => 'euw'
        ]]);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlRealms');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\RealmService');
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
