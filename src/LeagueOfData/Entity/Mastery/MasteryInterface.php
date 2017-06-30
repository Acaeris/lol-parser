<?php
namespace LeagueOfData\Entity\Mastery;

use LeagueOfData\Entity\EntityInterface;

/**
 * Mastery Interface
 *
 * @author caitlyn.osborne
 */
interface MasteryInterface extends EntityInterface
{
    /**
     * Mastery ID
     */
    public function getMasteryID() : int;

    /**
     * Mastery Name
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
     * Mastery Stats
     */
    public function getStats() : array;

    /**
     * Mastery Tags
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
