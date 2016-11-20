<?php

namespace LeagueOfData\Service\Interfaces;

interface ItemService {
    function findAll($verison);
    function find($id, $version);
}
