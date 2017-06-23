<?php

namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Entity\Champion\ChampionPassiveInterface;

class SqlChampionPassivesSpec extends ObjectBehavior
{
    private $mockData = [
        [
            'champion_id' => 1,
            'passive_name' => 'Pyromania',
            'image_name' => 'Annie_Passive',
            'description' => 'Test Description',
            'version' => '7.9.1',
            'region' => 'euw'
        ]
    ];

    public function let(Connection $dbConn, LoggerInterface $logger)
    {
        $dbConn->fetchAll('', [])->willReturn($this->mockData);
        $this->beConstructedWith($dbConn, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlChampionPassives');
        $this->shouldImplement('LeagueOfData\Service\StoreServiceInterface');
    }

    public function it_should_fetch_champion_passives()
    {
        $this->fetch("")->shouldReturnArrayOfChampionPassives();
    }

    public function it_can_convert_data_to_passive_object()
    {
        $this->create($this->mockData[0])->shouldImplement('LeagueOfData\Entity\Champion\ChampionPassiveInterface');
    }

    public function it_can_add_and_retrieve_spell_objects_from_collection(ChampionPassiveInterface $passive)
    {
        $passive->getChampionID()->willReturn(1);
        $this->add([$passive]);
        $this->transfer()->shouldReturnArrayOfChampionPassives();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfChampionPassives' => function(array $passives) {
                foreach ($passives as $passive) {
                    if (!$passive instanceof ChampionPassiveInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
