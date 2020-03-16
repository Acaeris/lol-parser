<?php
namespace App\Models\Champion;

use App\Models\EntityInterface;

/**
 * Champion Passive interface
 *
 * @author caitlyn.osborne
 */
interface ChampionPassiveInterface extends EntityInterface
{

    public function getChampionID(): int;

    public function getPassiveName(): string;

    public function getImageName(): string;

    public function getDescription(): string;

    public function getVersion(): string;

    public function getRegion(): string;
}
