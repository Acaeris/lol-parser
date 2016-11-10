<?php

namespace LeagueOfData\Models\Interfaces;

interface Summoner {
    public function id();
    public function level();
    public function name();
    public function icon();
    public function revisionDate();
}
