<?php

namespace App\Mappers\Champions;

use App\Mappers\Spells\SpellLevelTipMapper;
use App\Mappers\Spells\SpellVarMapper;
use App\Mappers\ImageMapper;
use App\Models\Champions\ChampionSpell;

class ChampionSpellMapper
{
    /**
     * Level tooltip mapper
     *
     * @var SpellLevelTipMapper
     */
    private $levelTipMapper;

    /**
     * Spell variable mapper
     *
     * @var SpellVarMapper
     */
    private $spellVarMapper;

    /**
     * Image mapper
     *
     * @var ImageMapper
     */
    private $imageMapper;

    /**
     * @param SpellLevelTipMapper $levelTipMapper - Level tooltip mapper
     * @param SpellVarMapper $spellVarMapper - Spell variable mapper
     * @param ImageMapper $imageMapper - Image mapper
     */
    public function __construct(
        SpellLevelTipMapper $levelTipMapper,
        SpellVarMapper $spellVarMapper,
        ImageMapper $imageMapper
    ) {
        $this->levelTipMapper = $levelTipMapper;
        $this->spellVarMapper = $spellVarMapper;
        $this->imageMapper = $imageMapper;
    }

    /**
     * @param array $data - Data to be mapped
     * @return ChampionSpell - Mapped ChampionSpell object
     */
    public function mapFromArray(array $data): ChampionSpell
    {
        $image = $this->imageMapper->mapFromArray($data['image']);
        $levelTip = $this->levelTipMapper->mapFromArray($data['leveltip']);
        $vars = [];
        $altImages = [];

        foreach ($data['vars'] as $var) {
            $vars[] = $this->spellVarMapper->mapFromArray($var);
        }

        foreach (($data['altimages'] ?? []) as $altImage) {
            $altImages[] = $this->imageMapper->mapFromArray($altImage);
        }

        return new ChampionSpell(
            $data['key'],
            $data['name'],
            $data['description'],
            $data['sanitizedDescription'],
            $data['tooltip'],
            $data['sanitizedTooltip'],
            $data['resource'],
            $data['cost'],
            $data['costType'],
            $data['cooldown'],
            $image,
            $altImages,
            $levelTip,
            $vars,
            $data['effect'],
            $data['range'],
            $data['maxrank']
        );
    }
}
