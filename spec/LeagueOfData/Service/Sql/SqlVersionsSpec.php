<?php

namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Models\Interfaces\VersionInterface;

class SqlVersionsSpec extends ObjectBehavior
{
    private $mockData = ['7.4.3', '7.4.2'];
    
    public function let(AdapterInterface $adapter, LoggerInterface $logger, RequestInterface $request)
    {
        $adapter->fetch($request)->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlVersions');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\VersionServiceInterface');
    }

    public function it_should_find_all_version_data(RequestInterface $request)
    {
        $this->fetch($request)->shouldReturnArrayOfVersions();
    }

    public function it_can_convert_data_to_version_object()
    {
        $this->create($this->mockData[0])->shouldImplement('LeagueOfData\Models\Interfaces\VersionInterface');
    }

    public function it_can_add_and_retrieve_version_objects_from_collection(VersionInterface $version)
    {
        $version->getFullVersion()->willReturn('7.9.1');
        $this->add([$version]);
        $this->transfer()->shouldReturnArrayOfVersions();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfVersions' => function($versions) {
                foreach ($versions as $version) {
                    if (!$version instanceof VersionInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}