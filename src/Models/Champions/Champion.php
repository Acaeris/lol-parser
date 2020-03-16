<?php

namespace App\Models\Champions;

use App\Models\Image;

class Champion
{
    /**
     * Champion ID
     *
     * @var int
     */
    private $championId;

    /**
     * Champion Name
     *
     * @var string
     */
    private $name;

    /**
     * Champion Title
     *
     * @var string
     */
    private $title;

    /**
     * Champion Artwork
     *
     * @var Image
     */
    private $image;

    /**
     * Champion Information
     *
     * @var ChampionInfo
     */
    private $info;

    /**
     * Champion Stats
     *
     * @var ChampionStats
     */
    private $stats;

    /**
     * Champion category tags
     *
     * @var string[]
     */
    private $tags;

    /**
     * Champion Partype
     *
     * @var string
     */
    private $partype;

    /**
     * Champion Skins
     *
     * @var ChampionSkin[]
     */
    private $skins;

    /**
     * Champion Passive
     *
     * @var ChampionPassive
     */
    private $passive;

    /**
     * Champion Spells
     *
     * @var ChampionSpell[]
     */
    private $spells;

    /**
     * Champion Recommendations
     *
     * @var ChampionRecommended[]
     */
    private $recommendations;

    /**
     * Ally and Enemy Tips
     *
     * @var string[]
     */
    private $tips;

    /**
     * Champion Key
     *
     * @var string
     */
    private $key;

    /**
     * Champion Lore
     *
     * @var string
     */
    private $lore;

    /**
     * Champion Blurb
     *
     * @var string
     */
    private $blurb;

    /**
     * @param int $championId - Champion ID
     * @param string $name - Champion name
     * @param string $title - Title
     * @param Image $image - Icon
     * @param ChampionInfo $info - Champion information
     * @param ChampionStats $stats - Champion stats
     * @param string[] $tags - Category tags
     * @param string $partype - Resource type
     * @param ChampionSkin[] $skins - Champion Skins
     * @param ChampionPassive $passive - Passive ability
     * @param ChampionSpell[] $spells - Active abilities
     * @param ChampionRecommended[] $recommendations - Recommended items
     * @param array[] $tips - Ally and Enemy tips
     * @param string $key - Key
     * @param string $lore - Lore text
     * @param string $blurb - Blurb text
     */
    public function __construct(
        int $championId,
        string $name,
        string $title,
        Image $image,
        ChampionInfo $info,
        ChampionStats $stats,
        array $tags,
        string $partype,
        array $skins,
        ChampionPassive $passive,
        array $spells,
        array $recommendations,
        array $tips,
        string $key,
        string $lore,
        string $blurb
    ) {
        $this->championId = $championId;
        $this->name = $name;
        $this->title = $title;
        $this->image = $image;
        $this->info = $info;
        $this->stats = $stats;
        $this->tags = $tags;
        $this->partype = $partype;
        $this->skins = $skins;
        $this->passive = $passive;
        $this->spells = $spells;
        $this->recommendations = $recommendations;
        $this->tips = $tips;
        $this->key = $key;
        $this->lore = $lore;
        $this->blurb = $blurb;
    }

    public function getId(): int
    {
        return $this->championId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function getInfo(): ChampionInfo
    {
        return $this->info;
    }

    public function getStats(): ChampionStats
    {
        return $this->stats;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getPartype(): string
    {
        return $this->partype;
    }

    public function getSkins(): array
    {
        return $this->skins;
    }

    public function getPassive(): ChampionPassive
    {
        return $this->passive;
    }

    public function getSpells(): array
    {
        return $this->spells;
    }

    public function getRecommendations(): array
    {
        return $this->recommendations;
    }

    public function getTips(): array
    {
        return $this->tips;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getLore(): string
    {
        return $this->lore;
    }

    public function getBlurb(): string
    {
        return $this->blurb;
    }
}
