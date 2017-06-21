<?php
namespace LeagueOfData\Models\Interfaces;

/**
 * Champion Passive interface
 *
 * @author caitlyn.osborne
 */
interface ChampionPassiveInterface
{

    public function getChampionID(): int;

    public function getPassiveName(): string;

    public function getImageName(): string;

    public function getDescription(): string;

    public function getVersion(): string;

    public function getRegion(): string;
}
