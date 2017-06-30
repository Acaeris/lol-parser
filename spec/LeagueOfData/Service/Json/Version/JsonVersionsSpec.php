<?php

namespace spec\LeagueOfData\Service\Json\Version;

use PhpSpec\ObjectBehavior;

use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\Version\VersionInterface;

class VersionCollectionSpec extends ObjectBehavior
{
    private $mockData = ['7.5.2', '7.5.1', '7.4.3'];

    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $adapter->fetch('static-data/v3/versions', ["region" => "euw"])->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\Version\VersionCollection');
        $this->shouldImplement('LeagueOfData\Service\FetchServiceInterface');
    }

    public function it_should_find_all_version_data()
    {
        $this->fetch([])->shouldReturnArrayOfVersions();
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
