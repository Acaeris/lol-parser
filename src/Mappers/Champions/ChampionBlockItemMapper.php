<?php

namespace App\Mappers\Champions;

use App\Models\Champions\ChampionBlockItem;

class ChampionBlockItemMapper
{
    public function mapFromArray(array $data): ChampionBlockItem
    {
        return new ChampionBlockItem(
            $data['id'],
            $data['count']
        );
    }
}
