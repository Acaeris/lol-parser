<?php

namespace LeagueOfData\Entity\Match;

use LeagueOfData\Entity\EntityInterface;

/**
 * Match Player Interface
 *
 * @author caitlyn.osborne
 */
interface MatchPlayerInterface extends EntityInterface
{
    public function getMatchID(): int;
    public function getAccountID(): int;
    public function getChampionID(): int;
    public function getParticipantID(): int;
    public function getRegion(): string;
}
