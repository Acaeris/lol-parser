<?php
namespace App\Models\Masteries;

use App\Models\EntityInterface;
use App\Models\Image;

/**
 * Mastery Interface
 *
 * @author caitlyn.osborne
 */
interface MasteryInterface extends EntityInterface
{
    /**
     * @return int Mastery ID
     */
    public function getMasteryID() : int;

    /**
     * @return string Mastery Name
     */
    public function getMasteryName() : string;

    /**
     * @return string[] Description
     */
    public function getDescription() : array;

    /**
     * @return int Ranks
     */
    public function getRanks() : int;

    /**
     * @return Image Icon
     */
    public function getIcon() : Image;

    /**
     * @return string Prerequisite
     */
    public function getPrerequisite() : string;

    /**
     * @return string Mastery Tree
     */
    public function getMasteryTree() : string;

    /**
     * @return string Version
     */
    public function getVersion() : string;

    /**
     * @return string Region
     */
    public function getRegion() : string;
}
