<?php

namespace LeagueOfData\Models;

interface History {
    public function date();
    public function type();
    public function data();
}
