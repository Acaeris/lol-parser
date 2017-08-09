<?php
namespace spec\LeagueOfData\Repository\Champion;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument\Token\AnyValuesToken;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use LeagueOfData\Entity\Champion\ChampionInterface;
use LeagueOfData\Entity\Champion\ChampionStatsInterface;
use LeagueOfData\Entity\Champion\ChampionSpellInterface;
use LeagueOfData\Entity\Champion\ChampionPassiveInterface;
use LeagueOfData\Repository\Champion\SqlChampionStatsRepository;
use LeagueOfData\Repository\Champion\SqlChampionSpellRepository;
use LeagueOfData\Repository\Champion\SqlChampionPassiveRepository;

class SqlChampionRepositorySpec extends ObjectBehavior
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
        SqlChampionStatsRepository $statRepository,
        SqlChampionSpellRepository $spellRepository,
        SqlChampionPassiveRepository $passiveRepository,
        ChampionStatsInterface $stats,
        ChampionSpellInterface $spell,
        ChampionPassiveInterface $passive
    ) {
        $dbConn->fetchAll(new AnyValuesToken)->willReturn($this->mockData);
        $statRepository->fetch(new AnyValuesToken)->willReturn([266 => $stats]);
        $spellRepository->fetch(new AnyValuesToken)->willReturn([266 => [$spell]]);
        $passiveRepository->fetch(new AnyValuesToken)->willReturn([266 => $passive]);
        $this->beConstructedWith($dbConn, $logger, $statRepository, $spellRepository, $passiveRepository);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Champion\SqlChampionRepository');
        $this->shouldImplement('LeagueOfData\Repository\StoreRepositoryInterface');
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
            'returnArrayOfChampions' => function (array $champions) {
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
