<?php

namespace LeagueOfData\Service\Interfaces;

interface RealmService
{
    /**
     * Find all Realm data
     *
     * @return array Realm objects
     */
    function findAll() : array;
}
