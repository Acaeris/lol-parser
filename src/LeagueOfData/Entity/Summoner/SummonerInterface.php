<?php

namespace LeagueOfData\Entity\Summoner;

use LeagueOfData\Entity\EntityInterface;

interface SummonerInterface extends EntityInterface
{
    /**
     * Summoner ID
     */
    public function getSummonerID() : int;

    /**
     * Account ID
     */
    public function getAccountID() : int;

    /**
     * Summoner Name
     */
    public function getName() : string;

    /**
     * Summoner Level
     */
    public function getLevel() : int;

    /**
     * Profile Icon ID
     */
    public function getProfileIconID() : int;

    /**
     * Revision Date
     */
    public function getRevisionDate() : \DateTime;

    /**
     * Region
     */
    public function getRegion() : string;
}
