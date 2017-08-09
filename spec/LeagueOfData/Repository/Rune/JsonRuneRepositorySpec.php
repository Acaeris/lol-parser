<?php
namespace spec\LeagueOfData\Repository\Rune;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use LeagueOfData\Adapters\AdapterInterface;
use LeagueOfData\Entity\Rune\RuneInterface;

class JsonRuneRepositorySpec extends ObjectBehavior
{
    private $mockData = [
        "data" => [
            "5001" => [
                "stats" => [
                    "FlatPhysicalDamageMod" => 0.525
                ],
                "description" => "+0.53 attack damage",
                "tags" => [
                    "physicalAttack",
                    "flat",
                    "mark"
                ],
                "image" => [
                    "full" => "r_1_1.png",
                    "group" => "rune",
                    "sprite" => "rune0.png",
                    "h" => 48,
                    "w" => 48,
                    "y" => 0,
                    "x" => 96
                ],
                "sanitizedDescription" => "+0.53 attack damage",
                "rune" => [
                    "tier" => "1",
                    "type" => "red",
                    "isRune" => true
                ],
                "id" => 5001,
                "name" => "Lesser Mark of Attack Damage",
                'region' => 'euw',
                'version' => '7.9.1'
            ]
        ]
    ];

    public function let(AdapterInterface $adapter, LoggerInterface $logger)
    {
        $adapter->fetch()->willReturn($this->mockData);
        $this->beConstructedWith($adapter, $logger);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Repository\Rune\JsonRuneRepository');
        $this->shouldImplement('LeagueOfData\Repository\FetchRepositoryInterface');
    }

    public function it_should_fetch_rune_data(AdapterInterface $adapter)
    {
        $params = ["region" => "euw", "tags" => "all", "version" => "7.9.1"];
        $adapter->setOptions("static-data/v3/runes", $params)->willReturn($adapter);
        $this->fetch(['version' => '7.9.1'])->shouldReturnArrayOfRunes();
    }

    public function it_can_convert_data_to_rune_object()
    {
        $this->create($this->mockData['data']['5001'])
            ->shouldImplement('LeagueOfData\Entity\Rune\RuneInterface');
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfRunes' => function (array $runes) : bool {
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
