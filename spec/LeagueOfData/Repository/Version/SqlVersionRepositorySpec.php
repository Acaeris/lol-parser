<?php

namespace spec\LeagueOfData\Repository\Version;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Entity\Version\VersionInterface;

class SqlVersionRepositorySpec extends ObjectBehavior
{
    private $mockData = [
        [
            'full_version' => '7.4.3'
        ],[
            'full_version' => '7.4.2'
        ]
    ];

    public function let(Connection $database, LoggerInterface $logger)
    {
        $database->fetchAll("", [])->willReturn($this->mockData);
        $this->beConstructedWith($database, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Version\SqlVersionRepository');
        $this->shouldImplement('LeagueOfData\Repository\StoreRepositoryInterface');
    }

    public function it_should_find_all_version_data()
    {
        $this->fetch("")->shouldReturnArrayOfVersions();
    }

    public function it_can_convert_data_to_version_object()
    {
        $this->create($this->mockData[0])->shouldImplement('LeagueOfData\Entity\Version\VersionInterface');
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
