<?php

namespace App\Models\Champion;

/**
 * Champion Defense Interface
 *
 * @author caitlyn.osborne
 */
interface ChampionDefenseInterface
{
    /**
     * Defence type
     *
     * @return string Defence type
     */
    public function getType() : string;
}
