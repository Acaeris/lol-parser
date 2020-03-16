<?php

namespace App\Mappers\Champions;

use App\Models\Champions\ChampionBlock;

class ChampionBlockMapper
{
    /**
     * Block item mapper
     *
     * @var ChampionBlockItemMapper
     */
    private $blockItemMapper;

    /**
     * @param ChampionBlockItemMapper $blockItemMapper - Block item mapper
     */
    public function __construct(ChampionBlockItemMapper $blockItemMapper)
    {
        $this->blockItemMapper = $blockItemMapper;
    }

    /**
     * @param array $data - Data to map
     * @return ChampionBlock - Mapped ChampionBlock object
     */
    public function mapFromArray(array $data): ChampionBlock
    {
        $items = [];

        foreach ($data['items'] as $item) {
            $items[] = $this->blockItemMapper->mapFromArray($item);
        }

        return new ChampionBlock(
            $items,
            $data['type'],
            $data['recMath']
        );
    }
}
