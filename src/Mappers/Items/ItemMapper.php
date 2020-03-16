<?php

namespace App\Mappers\Items;

use App\Mappers\ImageMapper;
use App\Mappers\StatMapper;
use App\Models\Items\Item;
use App\Models\Items\ItemGold;
use App\Models\Items\ItemInterface;

class ItemMapper
{
    /* @var ImageMapper Image object mapper */
    private $imageMapper;

    /* @var StatMapper Stat object mapper */
    private $statMapper;

    public function __construct(
        ImageMapper $imageMapper,
        StatMapper $statMapper
    ) {
        $this->imageMapper = $imageMapper;
        $this->statMapper = $statMapper;
    }

    public function mapFromArray(array $data): ItemInterface
    {
        $stats = [];

        foreach ($data['stats'] as $stat) {
            $stats[] = $this->statMapper->mapFromArray($stat);
        }

        return new Item(
            $data['item_id'],
            $data['item_name'],
            $data['colloq'],
            $this->imageMapper->mapFromArray($data['image']),
            $data['description'],
            $data['sanitized_description'],
            $data['tags'],
            $data['plain_text'],
            $stats,
            new ItemGold($data['base_value'], $data['total_value'], $data['sell_value'], $data['purchasable']),
            $data['effects'],
            $data['hide_from_all'],
            $data['in_store'],
            $data['consume_on_full'],
            $data['consumed'],
            $data['into'],
            $data['from'],
            $data['maps'],
            $data['special_recipe'],
            $data['required_champion'],
            $data['group'],
            $data['depth'],
            $data['stacks']
        );
    }
}
