<?php

namespace App\Models\Champions;

class ChampionRecommended
{
    /**
     * Map
     *
     * @var string
     */
    private $map;

    /**
     * Champion Name
     *
     * @var string
     */
    private $championName;

    /**
     * Title
     *
     * @var string
     */
    private $title;

    /**
     * Game mode
     *
     * @var string
     */
    private $mode;

    /**
     * Type
     *
     * @var string
     */
    private $type;

    /**
     * Priority
     *
     * @var bool
     */
    private $priority;

    /**
     * Blocks
     *
     * @var ChampionBlock[]
     */
    private $blocks;

    /**
     * @param string $map - Map code name
     * @param string $championName - Champion name
     * @param string $title - Section title
     * @param string $mode - Game mode
     * @param string $type - Recommendation type
     * @param bool $priority - ??
     * @param ChampionBlock[] $blocks - Blocks of items
     */
    public function __construct(
        string $map,
        string $championName,
        string $title,
        string $mode,
        string $type,
        bool $priority,
        array $blocks
    ) {
        $this->map = $map;
        $this->championName = $championName;
        $this->title = $title;
        $this->mode = $mode;
        $this->type = $type;
        $this->priority = $priority;
        $this->blocks = $blocks;
    }

    /**
     * @return string - Map code name
     */
    public function getMap(): string
    {
        return $this->map;
    }

    /**
     * @return string - Champion name
     */
    public function getChampionName(): string
    {
        return $this->championName;
    }

    /**
     * @return string - Section title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string - Game mode
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @return string - Recommendation type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool - ??
     */
    public function isPriority(): bool
    {
        return $this->priority;
    }

    /**
     * @return ChampionBlock[] - Blocks of items
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }
}
