<?php

namespace LeagueOfData\Service\Interfaces;

/**
 * Match History Service interface
 * @package LeagueOfData\Service|Interfaces
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface MatchHistoryServiceInterface
{
    public function add($id, $region);
    public function find($id);
}
