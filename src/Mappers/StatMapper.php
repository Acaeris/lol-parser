<?php

namespace App\Mappers;

use App\Models\Stat;

class StatMapper
{
    public function mapFromArray(array $data): Stat
    {
        return new Stat($data['name'], $data['stat']);
    }
}
