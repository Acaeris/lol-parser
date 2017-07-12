<?php

namespace LeagueOfData\Entity\Match;

use LeagueOfData\Entity\EntityInterface;

/**
 * Match Interface
 *
 * @author caitlyn.osborne
 */
interface MatchInterface extends EntityInterface
{
    public function getMatchID(): int;
}
