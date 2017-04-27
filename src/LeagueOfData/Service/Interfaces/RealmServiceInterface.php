<?php

namespace LeagueOfData\Service\Interfaces;

/**
 * Realm Service interface
 * @package LeagueOfData\Service|Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface RealmServiceInterface
{
    /**
     * fetch all Realm data
     *
     * @return array Realm objects
     */
    public function fetch() : array;
}
