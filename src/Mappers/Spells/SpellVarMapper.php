<?php

namespace App\Mappers\Spells;

use App\Models\Spells\SpellVar;

class SpellVarMapper
{
    /**
     * @param array $data - Data to map
     * @return SpellVar - Mapped SpellVar object
     */
    public function mapFromArray(array $data): SpellVar
    {
        return new SpellVar(
            $data['key'],
            $data['ranksWith'] ?? '',
            $data['dyn'] ?? '',
            $data['link'],
            $data['coeff']
        );
    }
}
