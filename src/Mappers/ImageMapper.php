<?php

namespace App\Mappers;

use App\Models\Image;

class ImageMapper
{
    public function mapFromArray(array $data): Image
    {
        return new Image(
            $data['full'],
            $data['group'],
            $data['sprite'],
            $data['x'],
            $data['y'],
            $data['w'],
            $data['h']
        );
    }
}
