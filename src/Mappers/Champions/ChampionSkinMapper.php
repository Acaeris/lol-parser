<?php

namespace App\Mappers\Champions;

use App\Models\Champions\ChampionSkin;

class ChampionSkinMapper
{
    public function mapFromArray(array $data): ChampionSkin
    {
        return new ChampionSkin(
            $data['id'],
            $data['num'],
            $data['name']
        );
    }
}
