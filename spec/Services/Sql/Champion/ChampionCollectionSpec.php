<?php
namespace spec\App\Services\Sql\Champion;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use App\Models\Champion\ChampionInterface;
use App\Models\Champion\ChampionStatsInterface;
use App\Models\Champion\ChampionSpellInterface;
use App\Models\Champion\ChampionPassiveInterface;
use App\Services\Sql\Champion\ChampionStatsCollection;
use App\Services\Sql\Champion\ChampionSpellCollection;
use App\Services\Sql\Champion\ChampionPassiveCollection;

class ChampionCollectionSpec extends ObjectBehavior
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
        ChampionStatsCollection $statService,
        ChampionSpellCollection $spellService,
        ChampionPassiveCollection $passiveService,
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
        $this->shouldHaveType('App\Services\Sql\Champion\ChampionCollection');
        $this->shouldImplement('App\Services\StoreServiceInterface');
    }

    public function it_should_fetch_champions()
    {
        $this->fetch("")->shouldReturnArrayOfChampions();
    }

    public function it_can_convert_data_to_champion_object()
    {
        $this->create($this->mockData[0])->shouldImplement('App\Models\Champion\ChampionInterface');
    }

    public function it_can_add_and_retrieve_champion_objects_from_collection(ChampionInterface $champion)
    {
        $champion->getChampionID()->willReturn(1);
        $this->add([$champion]);
        $this->transfer()->shouldReturnArrayOfChampions();
    }

    public function getMatchers(): array
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
