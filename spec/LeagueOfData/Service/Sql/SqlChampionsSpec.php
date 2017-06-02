<?php
namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request\ChampionStatsRequest;
use LeagueOfData\Adapters\Request\ChampionSpellRequest;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;
use LeagueOfData\Models\Interfaces\ChampionSpellInterface;
use LeagueOfData\Models\Interfaces\ChampionInterface;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;
use LeagueOfData\Service\Interfaces\ChampionSpellsServiceInterface;

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
        AdapterInterface $adapter,
        LoggerInterface $logger,
        ChampionStatsServiceInterface $statService,
        ChampionStatsInterface $stats,
        ChampionSpellsServiceInterface $spellService,
        ChampionSpellInterface $spell,
        RequestInterface $request)
    {
        $adapter->fetch($request)->willReturn($this->mockData);
        $aatroxStatRequest = new ChampionStatsRequest(['champion_id' => 266, 'version' => '7.9.1', 'region' => 'euw']);
        $statService->fetch($aatroxStatRequest)->willReturn([266 => $stats]);
        $aatroxSpellRequest = new ChampionSpellRequest(['champion_id' => 266, 'version' => '7.9.1', 'region' => 'euw']);
        $spellService->fetch($aatroxSpellRequest)->willReturn([266 => [$spell]]);
        $this->beConstructedWith($adapter, $logger, $statService, $spellService);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Service\Sql\SqlChampions');
        $this->shouldImplement('LeagueOfData\Service\Interfaces\ChampionServiceInterface');
    }

    public function it_should_fetch_champions(RequestInterface $request)
    {
        $this->fetch($request)->shouldReturnArrayOfChampions();
    }

    public function it_can_convert_data_to_champion_object()
    {
        $this->create($this->mockData[0])->shouldImplement('LeagueOfData\Models\Interfaces\ChampionInterface');
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
