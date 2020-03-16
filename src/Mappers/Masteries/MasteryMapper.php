<?php

namespace App\Mappers\Masteries;

use App\Mappers\ImageMapper;
use App\Models\Masteries\Mastery;
use App\Models\Masteries\MasteryInterface;

class MasteryMapper
{
    /* @var ImageMapper Image object mapper */
    private $imageMapper;

    public function __construct(ImageMapper $imageMapper)
    {
        $this->imageMapper = $imageMapper;
    }

    public function mapFromArray(array $data): MasteryInterface
    {
        return new Mastery(
            $data['mastery_id'],
            $data['mastery_name'],
            $data['description'],
            $this->imageMapper->mapFromArray($data['image']),
            $data['prereq'],
            $data['ranks'],
            $data['mastery_tree'],
            $data['region'],
            $data['version']
        );
    }
}
