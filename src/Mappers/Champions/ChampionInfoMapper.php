<?php

namespace App\Mappers\Champions;

use App\Models\Champions\ChampionInfo;

class ChampionInfoMapper
{
    public function mapFromArray(array $data): ChampionInfo
    {
        return new ChampionInfo(
            $data['difficulty'],
            $data['attack'],
            $data['defense'],
            $data['magic']
        );
    }
}
