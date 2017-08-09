<?php

namespace spec\LeagueOfData\Entity\Item;

use PhpSpec\ObjectBehavior;
use LeagueOfData\Entity\StatInterface;

class ItemSpec extends ObjectBehavior
{
    public function let(StatInterface $itemStat)
    {
        $itemStat->getStatName()->willReturn('moveSpeed');
        $itemStat->getStatModifier()->willReturn((float) 30);
        $this->beConstructedWith(
            1, // Item ID
            'Infinity Edge', // Item Name
            'Test Description', // Item Description
            300, // Purchase Cost
            210, // Sale Value
            [$itemStat], // Item Stats
            '7.4.3', // Version
            'euw' // Region
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LeagueOfData\Entity\Item\Item');
        $this->shouldImplement('LeagueOfData\Entity\Item\ItemInterface');
    }

    public function it_is_immutable()
    {
        $this->shouldImplement('LeagueOfData\Library\Immutable\ImmutableInterface');
    }

    public function it_has_all_data_available()
    {
        $this->getItemID()->shouldReturn(1);
        $this->getName()->shouldReturn('Infinity Edge');
        $this->getDescription()->shouldReturn('Test Description');
        $this->getGoldToBuy()->shouldReturn(300);
        $this->getGoldFromSale()->shouldReturn(210);
        $this->getStats()->shouldReturnArrayOfStats();
        $this->getVersion()->shouldReturn('7.4.3');
        $this->getRegion()->shouldReturn('euw');
    }

    public function it_can_fetch_a_specific_stat()
    {
        $this->getStat('moveSpeed')->shouldReturn((float) 30);
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfStats' => function (array $stats) : bool {
                foreach ($stats as $stat) {
                    if (!$stat instanceof StatInterface) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
