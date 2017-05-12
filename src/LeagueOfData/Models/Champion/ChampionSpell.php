<?php

namespace LeagueOfData\Models\Champion;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;
use LeagueOfData\Models\Interfaces\ChampionSpellInterface;

/**
 * Champion Spell model
 * 
 * @author caitlyn.osborne
 */
class ChampionSpell implements ChampionSpellInterface, ImmutableInterface
{
    /**
     * @var int Champion ID
     */
    private $championId;
    /**
     * @var int Spell ID
     */
    private $spellId;
    /**
     * @var string Spell Name
     */
    private $spellName;
    /**
     * @var string Control key
     */
    private $key;
    /**
     * @var string Image Name
     */
    private $imageName;
    /**
     * @var int
     */
    private $maxRank;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $tooltip;

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    public function __construct(
        int $championId,
        int $spellId,
        string $spellName,
        string $key,
        string $imageName,
        int $maxRank,
        string $description,
        string $tooltip
    ) {
        $this->constructImmutable();

        $this->championId = $championId;
        $this->spellId = $spellId;
        $this->spellName = $spellName;
        $this->key = $key;
        $this->imageName = $imageName;
        $this->maxRank = $maxRank;
        $this->description = $description;
        $this->tooltip = $tooltip;
    }

    /**
     * Get Champion ID
     *
     * @return int
     */
    public function getChampionID() : int
    {
        return $this->championId;
    }

    /**
     * Get Spell ID
     *
     * @return int
     */
    public function getSpellID() : int
    {
        return $this->spellId;
    }

    /**
     * Get Spell Name
     *
     * @return string
     */
    public function getSpellName() : string
    {
        return $this->spellName;
    }

    /**
     * Get Spell control key
     *
     * @return string
     */
    public function getKey() : string
    {
        return $this->key;
    }

    /**
     * Get icon image filename
     * 
     * @return string
     */
    public function getImageName() : string
    {
        return $this->imageName;
    }

    /**
     * Get max spell rank
     *
     * @return int
     */
    public function getMaxRank() : int
    {
        return $this->maxRank;
    }

    /**
     * Get spell description
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Get spell tooltip
     *
     * @return string
     */
    public function getTooltip() : string
    {
        return $this->tooltip;
    }
}
