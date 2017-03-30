<?php

namespace LeagueOfData\Models\Interfaces;

interface StatInterface
{
    public function key() : string;
    public function value() : float;
    public function toArray() : array;
}
