<?php

namespace App\Mappers\Champions;

use App\Models\Champions\ChampionRecommended;

class ChampionRecommendedMapper
{
    /**
     * Champion block mapper
     *
     * @var ChampionBlockMapper
     */
    private $blockMapper;

    /**
     * @param ChampionBlockMapper $blockMapper - Block mapper
     */
    public function __construct(ChampionBlockMapper $blockMapper)
    {
        $this->blockMapper = $blockMapper;
    }

    /**
     * @param array $data - Data to map
     * @return ChampionRecommended - Mapped ChampionRecommended object
     */
    public function mapFromArray(array $data): ChampionRecommended
    {
        $blocks = [];

        foreach ($data['blocks'] as $block) {
            $blocks[] = $this->blockMapper->mapFromArray($block);
        }

        return new ChampionRecommended(
            $data['map'],
            $data['champion'],
            $data['title'],
            $data['mode'],
            $data['type'],
            $data['priority'] ?? false,
            $blocks
        );
    }
}
