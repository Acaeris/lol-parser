<?php
namespace LeagueOfData\Entity\Champion;

use LeagueOfData\Library\Immutable\ImmutableInterface;
use LeagueOfData\Library\Immutable\ImmutableTrait;

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

    /**
     * @var array
     */
    private $cooldowns;

    /**
     * @var array
     */
    private $ranges;

    /**
     * @var array
     */
    private $effects;

    /**
     * @var array
     */
    private $variables;

    /**
     * @var ChampionSpellResourceInterface
     */
    private $resource;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $region;

    use ImmutableTrait {
        __construct as constructImmutable;
    }

    public function __construct(
        int $championId,
        string $spellName,
        string $key,
        string $imageName,
        int $maxRank,
        string $description,
        string $tooltip,
        array $cooldowns,
        array $ranges,
        array $effects,
        array $variables,
        ChampionSpellResourceInterface $resource,
        string $version,
        string $region
    ) {
        $this->constructImmutable();

        $this->championId = $championId;
        $this->spellName = $spellName;
        $this->key = $key;
        $this->imageName = $imageName;
        $this->maxRank = $maxRank;
        $this->description = $description;
        $this->tooltip = $tooltip;
        $this->cooldowns = $cooldowns;
        $this->ranges = $ranges;
        $this->effects = $effects;
        $this->variables = $variables;
        $this->resource = $resource;
        $this->version = $version;
        $this->region = $region;
    }

    /**
     * Get key identifying data for the object
     *
     * @return array
     */
    public function getKeyData() : array
    {
        return [
            'champion_id' => $this->championId,
            'spell_name' => $this->spellName,
            'version' => $this->version,
            'region' => $this->region
        ];
    }

    /**
     * Get Champion ID
     *
     * @return int
     */
    public function getChampionID(): int
    {
        return $this->championId;
    }

    /**
     * Get Spell Name
     *
     * @return string
     */
    public function getSpellName(): string
    {
        return $this->spellName;
    }

    /**
     * Get Spell control key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get icon image filename
     * 
     * @return string
     */
    public function getImageName(): string
    {
        return $this->imageName;
    }

    /**
     * Get max spell rank
     *
     * @return int
     */
    public function getMaxRank(): int
    {
        return $this->maxRank;
    }

    /**
     * Get spell description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get spell tooltip
     *
     * @return string
     */
    public function getTooltip(): string
    {
        return $this->tooltip;
    }

    /**
     * Get spell cooldowns
     *
     * @return array
     */
    public function getCooldowns(): array
    {
        return $this->cooldowns;
    }

    /**
     * Get spell cooldown by rank
     *
     * @param int $rank
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getCooldownByRank(int $rank): int
    {
        if (!isset($this->cooldowns[$rank - 1])) {
            throw new \InvalidArgumentException('Rank too high for spell.');
        }
        return $this->cooldowns[$rank - 1];
    }

    /**
     * Get spell ranges
     *
     * @return array
     */
    public function getRanges(): array
    {
        return $this->ranges;
    }

    /**
     * Get spell range by rank
     *
     * @param int $rank
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getRangeByRank(int $rank): int
    {
        if (!isset($this->ranges[$rank - 1])) {
            throw new \InvalidArgumentException('Rank too high for spell.');
        }
        return $this->ranges[$rank - 1];
    }

    /**
     * Get spell effects
     *
     * @return array
     */
    public function getEffects(): array
    {
        return $this->effects;
    }

    /**
     * Get effect by key
     *
     * @param int $key
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getEffectByKey(int $key): array
    {
        if (!isset($this->effects[$key])) {
            throw new \InvalidArgumentException('Effect not available');
        }
        return $this->effects[$key];
    }

    /**
     * Get effect value by key and rank
     *
     * @param int $key
     * @param int $rank
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getEffectValue(int $key, int $rank): float
    {
        if (!isset($this->effects[$key]) || !isset($this->effects[$key][$rank])) {
            throw new \InvalidArgumentException('Effect not available or rank to high for spell.');
        }
        return $this->effects[$key][$rank - 1];
    }

    /**
     * Get spell variables
     *
     * @return array
     */
    public function getVars(): array
    {
        return $this->variables;
    }

    /**
     * Get spell resource
     *
     * @return ChampionSpellResourceInterface
     */
    public function getResource(): ChampionSpellResourceInterface
    {
        return $this->resource;
    }

    /**
     * Get spell version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Get spell region
     *
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }
}
