<?php

namespace spec\App\Services\Json\Version;

use PhpSpec\ObjectBehavior;

use Psr\Log\LoggerInterface;
use App\Adapters\AdapterInterface;
use App\Models\Version\VersionInterface;

class VersionCollectionSpec extends ObjectBehavior
{
    private $mockData = ['7.5.2', '7.5.1', '7.4.3'];

    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $adapter->setOptions('static-data/v3/versions', ["region" => "euw"])->willReturn($adapter);
        $adapter->fetch()->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('App\Services\Json\Version\VersionCollection');
        $this->shouldImplement('App\Services\FetchServiceInterface');
    }

    public function it_should_find_all_version_data()
    {
        $this->fetch([])->shouldReturnArrayOfVersions();
    }

    public function getMatchers(): array
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
