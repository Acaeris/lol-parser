<?php

namespace App\Models\Items;

use App\Models\EntityInterface;
use App\Models\Image;
use App\Models\Stat;

class Item implements ItemInterface, EntityInterface
{
    /* @var int Item ID */
    private $itemId;

    /* @var string Item name */
    private $itemName;

    /* @var string Colloquial name */
    private $colloq;

    /* @var Image Icon */
    private $icon;

    /* @var string Description */
    private $description;

    /* @var string Sanitized description */
    private $sanitizedDescription;

    /* @var string[] Tags */
    private $tags;

    /* @var string Plain text */
    private $plainText;

    /* @var Stat[] Item stats */
    private $stats;

    /* @var ItemGold Gold values */
    private $gold;

    /* @var string[] Effects */
    private $effects;

    /* @var bool Hide from all */
    private $hideFromAll;

    /* @var bool In store */
    private $inStore;

    /* @var bool Consume on full */
    private $consumeOnFull;

    /* @var bool Consumed */
    private $consumed;

    /* @var string[] Builds Into */
    private $into;

    /* @var string[] Builds From */
    private $from;

    /* @var bool[] Maps available on */
    private $maps;

    /* @var int Special recipe */
    private $specialRecipe;

    /* @var string Required champion */
    private $requiredChampion;

    /* @var string Group */
    private $group;

    /* @var int Depth */
    private $depth;

    /* @var int Stacks */
    private $stacks;

    /**
     * @param int $itemId Item ID
     * @param string $itemName Item name
     * @param string $colloq Colloquial name
     * @param Image $icon Item icon
     * @param string $description Description
     * @param string $sanitizedDescription Sanitized description
     * @param string[] $tags Tags
     * @param string $plainText Plain text
     * @param Stat[] $stats Item stats
     * @param ItemGold $gold Gold
     * @param string[] $effects Effects
     * @param bool $hideFromAll Hide from all
     * @param bool $inStore In store
     * @param bool $consumeOnFull Consume on full
     * @param bool $consumed Consumed
     * @param string[] $into Builds into
     * @param string[] $from Builds from
     * @param bool[] $maps Maps
     * @param int $specialRecipe Special recipe
     * @param string $requiredChampion Required champion
     * @param string $group Group
     * @param int $depth Depth
     * @param int $stacks Stacks
     */
    public function __construct(
        int $itemId,
        string $itemName,
        string $colloq,
        Image $icon,
        string $description,
        string $sanitizedDescription,
        array $tags,
        string $plainText,
        array $stats,
        ItemGold $gold,
        array $effects,
        bool $hideFromAll,
        bool $inStore,
        bool $consumeOnFull,
        bool $consumed,
        array $into,
        array $from,
        array $maps,
        int $specialRecipe,
        string $requiredChampion,
        string $group,
        int $depth,
        int $stacks
    ) {
        $this->itemId = $itemId;
        $this->itemName = $itemName;
        $this->colloq = $colloq;
        $this->icon = $icon;
        $this->description = $description;
        $this->sanitizedDescription = $sanitizedDescription;
        $this->tags = $tags;
        $this->plainText = $plainText;
        $this->stats = $stats;
        $this->gold = $gold;
        $this->effects = $effects;
        $this->hideFromAll = $hideFromAll;
        $this->inStore = $inStore;
        $this->consumeOnFull = $consumeOnFull;
        $this->consumed = $consumed;
        $this->into = $into;
        $this->from = $from;
        $this->maps = $maps;
        $this->specialRecipe = $specialRecipe;
        $this->requiredChampion = $requiredChampion;
        $this->group = $group;
        $this->depth = $depth;
        $this->stacks = $stacks;
    }

    public function getKeyData(): array
    {
        return [
            'item_id' => $this->itemId
        ];
    }

    /**
     * @return int Item ID
     */
    public function getItemID(): int
    {
        return $this->itemId;
    }

    /**
     * @return string Item name
     */
    public function getItemName(): string
    {
        return $this->itemName;
    }

    /**
     * @return string Colloquial name
     */
    public function getColloquialName(): string
    {
        return $this->colloq;
    }

    /**
     * @return Image Item icon
     */
    public function getImage(): Image
    {
        return $this->icon;
    }

    /**
     * @return string Description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string Sanitized description
     */
    public function getSanitizedDescription(): string
    {
        return $this->sanitizedDescription;
    }

    /**
     * @return string[] Tags
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return string Plain text
     */
    public function getPlainText(): string
    {
        return $this->plainText;
    }

    /**
     * @return Stat[] Item stats
     */
    public function getStats(): array
    {
        return $this->stats;
    }

    /**
     * @return ItemGold Item gold
     */
    public function getGold(): ItemGold
    {
        return $this->gold;
    }

    /**
     * @return string[] Effects
     */
    public function getEffects(): array
    {
        return $this->effects;
    }

    /**
     * @return bool Hidden from all
     */
    public function isHiddenFromAll(): bool
    {
        return $this->hideFromAll;
    }

    /**
     * @return bool In store
     */
    public function isInStore(): bool
    {
        return $this->inStore;
    }

    /**
     * @return bool Consume on full
     */
    public function isConsumeOnFull(): bool
    {
        return $this->consumeOnFull;
    }

    /**
     * @return bool Consumed
     */
    public function isConsumed(): bool
    {
        return $this->consumed;
    }

    /**
     * @return string[] Builds into
     */
    public function getInto(): array
    {
        return $this->into;
    }

    /**
     * @return string[] Built from
     */
    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * @return bool[] Maps
     */
    public function getMaps(): array
    {
        return $this->maps;
    }

    /**
     * @return int Special recipe
     */
    public function getSpecialRecipe(): int
    {
        return $this->specialRecipe;
    }

    /**
     * @return string Required champion
     */
    public function getRequiredChampion(): string
    {
        return $this->requiredChampion;
    }

    /**
     * @return string Group
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @return int Depth
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @return int Stacks
     */
    public function getStacks(): int
    {
        return $this->stacks;
    }
}
