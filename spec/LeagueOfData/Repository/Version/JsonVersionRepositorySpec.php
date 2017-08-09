<?php

namespace spec\LeagueOfData\Repository\Version;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Prophecy\Argument\Token\AnyValuesToken;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\Version\VersionInterface;

class JsonVersionRepositorySpec extends ObjectBehavior
{
    private $mockData = ['7.5.2', '7.5.1', '7.4.3'];

    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $adapter->fetch()->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Version\JsonVersionRepository');
        $this->shouldImplement('LeagueOfData\Repository\FetchRepositoryInterface');
    }

    public function it_should_find_all_version_data(AdapterInterface $adapter)
    {
        $adapter->setOptions(new AnyValuesToken)->willReturn($adapter);
        $this->fetch([])->shouldReturnArrayOfVersions();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfVersions' => function ($versions) {
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
