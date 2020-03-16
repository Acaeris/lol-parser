<?php

namespace App\Mappers\Champions;

use App\Mappers\ImageMapper;
use App\Models\Champions\ChampionPassive;

class ChampionPassiveMapper
{
    /**
     * Image mapper
     *
     * @var ImageMapper
     */
    private $imageMapper;

    public function __construct(
        ImageMapper $imageMapper
    ) {
        $this->imageMapper = $imageMapper;
    }

    public function mapFromArray(array $data): ChampionPassive
    {
        $image = $this->imageMapper->mapFromArray($data['image']);

        return new ChampionPassive(
            $data['name'],
            $image,
            $data['description'],
            $data['sanitizedDescription']
        );
    }
}
