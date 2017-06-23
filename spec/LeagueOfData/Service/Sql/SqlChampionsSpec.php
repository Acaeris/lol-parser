<?php
namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Adapters\Request\ChampionStatsRequest;
use LeagueOfData\Adapters\Request\ChampionSpellRequest;
use LeagueOfData\Adapters\Request\ChampionPassiveRequest;
use LeagueOfData\Entity\Champion\ChampionInterface;
use LeagueOfData\Entity\Champion\ChampionStatsInterface;
use LeagueOfData\Entity\Champion\ChampionSpellInterface;
use LeagueOfData\Entity\Champion\ChampionPassiveInterface;
use LeagueOfData\Service\StoreServiceInterface;

class SqlChampionsSpec extends ObjectBehavior
{
    private $mockData = [
        [
            "champion_id" => 266,
            "champion_name" => "Aatrox",
            "image_name" => "Aatrox",
            "title" => "the Darkin Blade",
            "tags" => "Fighter|Tank",
            "resource_type" => "Blood Well",
            "version" => "7.9.1",
            "region" => "euw"
        ]
    ];

    public function let(
        Connection $dbConn,
        LoggerInterface $logger,
        StoreServiceInterface $statService,
        StoreServiceInterface $spellService,
        StoreServiceInterface $passiveService,
        ChampionStatsInterface $stats,
        ChampionSpellInterface $spell,
        ChampionPassiveInterface $passive)
    {
        $where = ["version" => "7.9.1", "region" => "euw", "champion_id" => 266];
        $dbConn->fetchAll('', [])->willReturn($this->mockData);
        $statService->fetch("SELECT * FROM champion_stats WHERE version = :version AND region = :region"
            . " AND champion_id = :champion_id", $where)->willReturn([266 => $stats]);
        $spellService->fetch("SELECT * FROM champion_spells WHERE version = :version"
            . " AND region = :region AND champion_id = :champion_id", $where)->willReturn([266 => [$spell]]);
        $passiveService->fetch("SELECT * FROM champion_passives WHERE version = :version"
            . " AND region = :region AND champion_id = :champion_id", $where)->willReturn([266 => $passive]);
        $this->beConstructedWith($dbConn, $logger, $statService, $spellService, $passiveService);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlChampions');
        $this->shouldImplement('LeagueOfData\Service\StoreServiceInterface');
    }

    public function it_should_fetch_champions()
    {
        $this->fetch("")->shouldReturnArrayOfChampions();
    }

    public function it_can_convert_data_to_champion_object()
    {
        $this->create($this->mockData[0])->shouldImplement('LeagueOfData\Entity\Champion\ChampionInterface');
    }

    public function it_can_add_and_retrieve_champion_objects_from_collection(ChampionInterface $champion)
    {
        $champion->getChampionID()->willReturn(1);
        $this->add([$champion]);
        $this->transfer()->shouldReturnArrayOfChampions();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfChampions' => function(array $champions) {
                foreach ($champions as $champion) {
                    if (!$champion instanceof ChampionInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
