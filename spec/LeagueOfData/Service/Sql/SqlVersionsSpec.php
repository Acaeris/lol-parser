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
        $request = new VersionRequest(['fullversion' => '7.4.3'],
            'SELECT fullversion FROM version WHERE fullversion = :fullversion', 
                [
                    'fullversion' => '7.4.3'
                ]);
        $adapter->fetch($request)->willReturn(['7.4.3']);
        $request = new VersionRequest([], 'SELECT fullversion FROM version');
        $adapter->fetch($request)->willReturn(['7.4.3', '7.4.2']);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlVersions');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\VersionService');
    }

    public function it_should_find_all_version_data()
    {
        $this->findAll()->shouldReturnArrayOfVersions();
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