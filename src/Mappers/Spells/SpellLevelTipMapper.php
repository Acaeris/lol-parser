<?php

namespace App\Mappers\Spells;

use App\Models\Spells\SpellLevelTip;

class SpellLevelTipMapper
{
    public function mapFromArray(array $data): SpellLevelTip
    {
        return new SpellLevelTip(
            $data['label'],
            $data['effect']
        );
    }
}
