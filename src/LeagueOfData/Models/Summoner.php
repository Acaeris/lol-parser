<?php

namespace LeagueOfData\Models;

interface Summoner {
    public function id();
    public function level();
    public function name();
    public function icon();
    public function revisionDate();
}
