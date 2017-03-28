<?php

namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\Request\VersionRequest;
use LeagueOfData\Models\Version;
use Psr\Log\LoggerInterface;

class SqlVersionsSpec extends ObjectBehavior
{
    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $request = new VersionRequest(['full_version' => '7.4.3'],
            'full_version', 
            ['full_version' => '7.4.3']);
        $adapter->fetch($request)->willReturn(['7.4.3']);
        $request = new VersionRequest([], 'full_version');
        $adapter->fetch($request)->willReturn(['7.4.3', '7.4.2']);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlVersions');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\VersionServiceInterface');
    }

    public function it_should_find_all_version_data()
    {
        $this->fetch()->shouldReturnArrayOfVersions();
    }

    public function getMatchers()
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