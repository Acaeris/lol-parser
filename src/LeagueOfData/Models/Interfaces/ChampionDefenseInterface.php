<?php

namespace LeagueOfData\Models\Interfaces;

/**
 * Champion Defense Interface
 *
 * @author caitlyn.osborne
 * @see LeagueOfData\Models\ChampionDefense
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
