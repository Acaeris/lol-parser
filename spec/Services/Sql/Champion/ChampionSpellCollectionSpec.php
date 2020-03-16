<?php

namespace spec\App\Services\Sql\Champion;

use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use App\Models\Champion\ChampionSpellInterface;

class ChampionSpellCollectionSpec extends ObjectBehavior
{
    private $mockData = [
        [
            'champion_id' => 266,
            'spell_name' => 'Disintegrate',
            'spell_key' => 'Q',
            'image_name' => 'Disintegrate',
            'max_rank' => 5,
            'description' => 'Test Description',
            'tooltip' => 'Test Tooltip',
            'cooldowns' => '[6, 5, 4, 3, 2]',
            'ranges' => '[10, 20, 30, 40, 50]',
            'effects' => '[null, [1, 2, 3, 4, 5]]',
            'variables' => '[{"link":"spelldamage","coeff":[0.8, 0.8, 0.8, 0.8, 0.8],"key":"a1"}]',
            'resource' => '{"name": "Mana", "values": [60, 65, 70, 75, 80] }',
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
        $this->shouldHaveType('App\Services\Sql\Champion\ChampionSpellCollection');
        $this->shouldImplement('App\Services\StoreServiceInterface');
    }

    public function it_should_fetch_champion_spells()
    {
        $this->fetch("")->shouldReturnArrayOfChampionSpells();
    }

    public function it_can_convert_data_to_spell_object()
    {
        $this->create($this->mockData[0])->shouldImplement('App\Models\Champion\ChampionSpellInterface');
    }

    public function it_can_add_and_retrieve_spell_objects_from_collection(ChampionSpellInterface $spell)
    {
        $spell->getSpellName()->willReturn('Disintigrate');
        $this->add([$spell]);
        $this->transfer()->shouldReturnArrayOfChampionSpells();
    }

    public function getMatchers(): array
    {
        return [
            'returnArrayOfChampionSpells' => function(array $spells) {
                foreach ($spells as $spell) {
                    if (!$spell instanceof ChampionSpellInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
