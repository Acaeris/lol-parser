<?php

namespace LeagueOfData\Entity;

interface StatInterface
{
    /**
     * Stat Name
     */
    public function getStatName() : string;

    /**
     * Stat Modifier
     */
    public function getStatModifier() : float;
}
