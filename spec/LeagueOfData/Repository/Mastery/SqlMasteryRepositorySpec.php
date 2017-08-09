<?php
namespace spec\LeagueOfData\Repository\Mastery;

use PhpSpec\ObjectBehavior;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use LeagueOfData\Entity\Mastery\MasteryInterface;

class SqlMasteryRepositorySpec extends ObjectBehavior
{
    private $mockData = [
        [
            "mastery_id" => 6111,
            "mastery_name" => "Fury",
            "description" => "+0.8% Attack Speed|+1.6% Attack Speed|+2.4% Attack Speed|+3.2% Attack Speed|"
                . "+4% Attack Speed",
            "ranks" => 5,
            "image_name" => "6111.png",
            "mastery_tree" => "Ferocity",
            "version" => "7.9.1",
            "region" => "euw"
        ]
    ];

    public function let(Connection $dbConn, LoggerInterface $logger)
    {
        $dbConn->fetchAll('', [])->willReturn($this->mockData);
        $this->beConstructedWith($dbConn, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Mastery\SqlMasteryRepository');
        $this->shouldImplement('LeagueOfData\Repository\StoreRepositoryInterface');
    }

    public function it_should_fetch_mastery_data()
    {
        $this->fetch("")->shouldReturnArrayOfMasteries();
    }

    public function it_can_convert_data_to_mastery_objects()
    {
        $this->create($this->mockData[0])->shouldImplement('LeagueOfData\Entity\Mastery\MasteryInterface');
    }

    public function it_can_add_and_retrieve_mastery_objects_from_collection(MasteryInterface $mastery)
    {
        $mastery->getMasteryID()->willReturn(1);
        $this->add([$mastery]);
        $this->transfer()->shouldReturnArrayOfMasteries();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfMasteries' => function (array $masteries) : bool {
                foreach ($masteries as $mastery) {
                    if (!$mastery instanceof MasteryInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
