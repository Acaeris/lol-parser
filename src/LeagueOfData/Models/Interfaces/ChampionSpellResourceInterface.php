<?php

namespace LeagueOfData\Models\Interfaces;

interface ChampionSpellResourceInterface
{
    /**
     * Get the resource name
     */
    public function getName() : string;

    /**
     * Get the values
     */
    public function getValues() : array;

    /**
     * Get value by rank
     *
     * @param int $rank
     */
    public function getValueByRank(int $rank) : int;
}
