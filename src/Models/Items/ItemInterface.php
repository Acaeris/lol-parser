<?php

namespace App\Models\Items;

use App\Models\Image;
use App\Models\Stat;

interface ItemInterface
{
    /**
     * @return int Item ID
     */
    public function getItemID(): int;

    /**
     * @return string Item name
     */
    public function getItemName(): string;

    /**
     * @return string Colloquial name
     */
    public function getColloquialName(): string;

    /**
     * @return Image Item icon
     */
    public function getImage(): Image;

    /**
     * @return string Description
     */
    public function getDescription(): string;

    /**
     * @return string Sanitized description
     */
    public function getSanitizedDescription(): string;

    /**
     * @return string[] Tags
     */
    public function getTags(): array;

    /**
     * @return string Plain text
     */
    public function getPlainText(): string;

    /**
     * @return Stat[] Item stats
     */
    public function getStats(): array;

    /**
     * @return ItemGold Item gold
     */
    public function getGold(): ItemGold;

    /**
     * @return string[] Effects
     */
    public function getEffects(): array;

    /**
     * @return bool Hidden from all
     */
    public function isHiddenFromAll(): bool;

    /**
     * @return bool In store
     */
    public function isInStore(): bool;

    /**
     * @return bool Consume on full
     */
    public function isConsumeOnFull(): bool;

    /**
     * @return bool Consumed
     */
    public function isConsumed(): bool;

    /**
     * @return string[] Builds into
     */
    public function getInto(): array;

    /**
     * @return string[] Built from
     */
    public function getFrom(): array;

    /**
     * @return bool[] Maps
     */
    public function getMaps(): array;

    /**
     * @return int Special recipe
     */
    public function getSpecialRecipe(): int;

    /**
     * @return string Required champion
     */
    public function getRequiredChampion(): string;

    /**
     * @return string Group
     */
    public function getGroup(): string;

    /**
     * @return int Depth
     */
    public function getDepth(): int;

    /**
     * @return int Stacks
     */
    public function getStacks(): int;
}