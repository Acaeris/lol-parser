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
    public function getDescription() : array;

    /**
     * Ranks
     */
    public function getRanks()  : int;

    /**
     * Image Name
     */
    public function getImageName() : string;

    /**
     * Mastery Tree
     */
    public function getMasteryTree() : string;

    /**
     * Version
     */
    public function getVersion() : string;

    /**
     * Region
     */
    public function getRegion() : string;
}
