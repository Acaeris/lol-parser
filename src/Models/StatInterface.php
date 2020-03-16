<?php

namespace App\Models;

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
