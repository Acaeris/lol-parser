<?php
namespace LeagueOfData\Entity\Rune;

use LeagueOfData\Entity\EntityInterface;

/**
 * Rune Interface
 *
 * @author caitlyn.osborne
 */
interface RuneInterface extends EntityInterface
{
    /**
     * Rune ID
     */
    public function getRuneID() : int;

    /**
     * Rune Name
     */
    public function getName() : string;

    /**
     * Description
     */
    public function getDescription() : string;

    /**
     * Image Name
     */
    public function getImageName() : string;

    /**
     * Rune Stats
     */
    public function getStats() : array;

    /**
     * Rune Tags
     */
    public function getTags() : array;

    /**
     * Version
     */
    public function getVersion() : string;

    /**
     * Region
     */
    public function getRegion() : string;

    /**
     * Specific stat value
     */
    public function getStat(string $key) : float;
}
