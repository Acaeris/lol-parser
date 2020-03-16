<?php

namespace spec\App\Services\Sql\Realm;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use App\Models\Realm\RealmInterface;

class RealmCollectionSpec extends ObjectBehavior
{
    private $mockData = [[
        'cdn' => 'http://ddragon.leagueoflegends.com/cdn',
        'version' => '7.4.3'
    ]];

    public function let(Connection $dbConn, LoggerInterface $logger)
    {
        $dbConn->fetchAll('', [])->willReturn($this->mockData);
        $this->beConstructedWith($dbConn, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('App\Services\Sql\Realm\RealmCollection');
        $this->shouldImplement('App\Services\StoreServiceInterface');
    }

    public function it_should_find_all_realm_data()
    {
        $this->fetch("")->shouldReturnArrayOfRealms();
    }

    public function it_can_convert_data_to_realm_object()
    {
        $this->create($this->mockData[0])->shouldImplement('App\Models\Realm\RealmInterface');
    }

    public function it_can_add_and_retrieve_spell_objects_from_collection(RealmInterface $realm)
    {
        $realm->getVersion()->willReturn('7.9.1');
        $this->add([$realm]);
        $this->transfer()->shouldReturnArrayOfRealms();
    }

    public function getMatchers(): array
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
