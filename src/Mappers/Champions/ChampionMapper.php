<?php

namespace App\Mappers\Champions;

use App\Mappers\ImageMapper;
use App\Models\Champions\Champion;

class ChampionMapper
{
    /**
     * Champion info data mapper
     *
     * @var ChampionInfoMapper
     */
    private $infoMapper;

    /**
     * Champion stats mapper
     *
     * @var ChampionStatsMapper
     */
    private $statsMapper;

    /**
     * Image mapper
     *
     * @var ImageMapper
     */
    private $imageMapper;

    /**
     * Champion skin mapper
     *
     * @var ChampionSkinMapper
     */
    private $skinMapper;

    /**
     * Champion passive mapper
     *
     * @var ChampionPassiveMapper
     */
    private $passiveMapper;

    /**
     * Champion recommended mapper
     *
     * @var ChampionRecommendedMapper
     */
    private $recommendedMapper;

    /**
     * Champion spell mapper
     *
     * @var ChampionSpellMapper
     */
    private $spellMapper;

    /**
     * @param ChampionInfoMapper $infoMapper - Information mapper
     * @param ChampionStatsMapper $statsMapper - Stats mapper
     * @param ImageMapper $imageMapper - Image mapper
     * @param ChampionSkinMapper $skinMapper - Skin mapper
     * @param ChampionPassiveMapper $passiveMapper - Passive ability mapper
     * @param ChampionRecommendedMapper $recommendedMapper - Build path mapper
     * @param ChampionSpellMapper $spellMapper - Spell mapper
     */
    public function __construct(
        ChampionInfoMapper $infoMapper,
        ChampionStatsMapper $statsMapper,
        ImageMapper $imageMapper,
        ChampionSkinMapper $skinMapper,
        ChampionPassiveMapper $passiveMapper,
        ChampionRecommendedMapper $recommendedMapper,
        ChampionSpellMapper $spellMapper
    ) {
        $this->infoMapper = $infoMapper;
        $this->statsMapper = $statsMapper;
        $this->imageMapper = $imageMapper;
        $this->skinMapper = $skinMapper;
        $this->passiveMapper = $passiveMapper;
        $this->recommendedMapper = $recommendedMapper;
        $this->spellMapper = $spellMapper;
    }

    public function mapFromArray(array $data): Champion
    {
        $skins = [];
        $recommends = [];
        $spells = [];

        $info = $this->infoMapper->mapFromArray($data['info']);
        $stats = $this->statsMapper->mapFromArray($data['stats']);
        $image = $this->imageMapper->mapFromArray($data['image']);
        $passive = $this->passiveMapper->mapFromArray($data['passive']);

        foreach ($data['skins'] as $skin) {
            $skins[] = $this->skinMapper->mapFromArray($skin);
        }

        foreach ($data['recommended'] as $recommended) {
            $recommends[] = $this->recommendedMapper->mapFromArray($recommended);
        }

        foreach ($data['spells'] as $spell) {
            $spells[] = $this->spellMapper->mapFromArray($spell);
        }

        return new Champion(
            $data['id'],
            $data['name'],
            $data['title'],
            $image,
            $info,
            $stats,
            $data['tags'],
            $data['partype'],
            $skins,
            $passive,
            $spells,
            $recommends,
            ['allytips' => $data['allytips'], 'enemytips' => $data['enemytips']],
            $data['key'],
            $data['lore'],
            $data['blurb']
        );
    }
}
