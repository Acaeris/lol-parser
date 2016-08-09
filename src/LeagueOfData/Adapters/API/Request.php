<?php

namespace LeagueOfData\Adapters\API;

interface Request {
    public function call();
    public function params();
}
