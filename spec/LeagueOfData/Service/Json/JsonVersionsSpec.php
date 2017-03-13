<?php

namespace spec\LeagueOfData\Service\Json;

use PhpSpec\ObjectBehavior;

use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\VersionRequest;
use LeagueOfData\Models\Version;
use Psr\Log\LoggerInterface;

class JsonVersionsSpec extends ObjectBehavior
{
    function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $request = new VersionRequest([]);
        $adapter->fetch($request)->willReturn([
            '7.5.2',
            '7.5.1',
            '7.4.3'
        ]);
        $this->beConstructedWith($adapter, $logger);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Json\JsonVersions');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\VersionService');
    }

    function it_should_find_all_version_data()
    {
        $this->findAll()->shouldReturnArrayOfVersions();
    }

    function getMatchers()
    {
        return [
            'returnArrayOfVersions' => function($versions) {
                foreach ($versions as $version) {
                    if (!$version instanceof Version) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}