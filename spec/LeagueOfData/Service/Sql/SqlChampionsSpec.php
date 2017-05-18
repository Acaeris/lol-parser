<?php
namespace spec\LeagueOfData\Service\Sql;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request\ChampionStatsRequest;
use LeagueOfData\Models\Interfaces\ChampionStatsInterface;
use LeagueOfData\Models\Interfaces\ChampionInterface;
use LeagueOfData\Service\Interfaces\ChampionStatsServiceInterface;

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
        ], [
            "champion_id" => 412,
            "champion_name" => "Thresh",
            "image_name" => "Aatrox",
            "title" => "the Chain Warden",
            "tags" => "Support",
            "resource_type" => "Mana",
            "version" => "7.9.1",
            "region" => "euw"
        ]
    ];

    public function let(
        AdapterInterface $adapter,
        LoggerInterface $logger,
        ChampionStatsServiceInterface $statBuilder,
        ChampionStatsInterface $stats,
        RequestInterface $request)
    {
        $aatroxStatRequest = new ChampionStatsRequest(['champion_id' => 266, 'version' => '7.9.1', 'region' => 'euw']);
        $threshStatRequest = new ChampionStatsRequest(['champion_id' => 412, 'version' => '7.9.1', 'region' => 'euw']);
        $adapter->fetch($request)->willReturn($this->mockData);
        $statBuilder->fetch($aatroxStatRequest)->willReturn([266 => $stats]);
        $statBuilder->fetch($threshStatRequest)->willReturn([412 => $stats]);
        $this->beConstructedWith($adapter, $logger, $statBuilder);
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
