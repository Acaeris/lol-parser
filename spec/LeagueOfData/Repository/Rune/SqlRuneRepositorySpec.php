<?php
namespace spec\LeagueOfData\Repository\Rune;

use PhpSpec\ObjectBehavior;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use LeagueOfData\Entity\Rune\RuneInterface;

class SqlRuneRepositorySpec extends ObjectBehavior
{
    private $mockData = [
        [
            "rune_id" => 5001,
            "rune_name" => "Lesser Mark of Attack Damage",
            "description" => "+0.53 attack damage",
            "image_name" => "r_1_1.png",
            "tags" => "physicalDamage|flat|mark",
            "version" => "7.9.1",
            "region" => "euw"
        ]
    ];
    private $mockStats = [
        [
            "rune_id" => 5001,
            "stat_name" => "FlatPhysicalDamageMod",
            "stat_value" => 0.525,
            "version" => "7.9.1",
            "region" => "euw"
        ]
    ];

    public function let(Connection $dbConn, LoggerInterface $logger)
    {
        $dbConn->fetchAll('', [])->willReturn($this->mockData);
        $select = "SELECT * FROM rune_stats WHERE rune_id = :rune_id AND version = :version AND region = :region";
        $statRequest = ['rune_id' => 5001, 'version' => '7.9.1', 'region' => 'euw'];
        $dbConn->fetchAll($select, $statRequest)->willReturn($this->mockStats);
        $this->beConstructedWith($dbConn, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Rune\SqlRuneRepository');
        $this->shouldImplement('LeagueOfData\Repository\StoreRepositoryInterface');
    }

    public function it_should_fetch_rune_data()
    {
        $this->fetch("")->shouldReturnArrayOfRunes();
    }

    public function it_can_convert_data_to_rune_objects()
    {
        $data = array_merge($this->mockData[0], ['stats' => $this->mockStats]);
        $this->create($data)->shouldImplement('LeagueOfData\Entity\Rune\RuneInterface');
    }

    public function it_can_add_and_retrieve_rune_objects_from_collection(RuneInterface $rune)
    {
        $rune->getRuneID()->willReturn(1);
        $this->add([$rune]);
        $this->transfer()->shouldReturnArrayOfRunes();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfRunes' => function(array $runes) : bool {
                foreach ($runes as $rune) {
                    if (!$rune instanceof RuneInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
