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
    public function getMode(): string;
    public function getRegion(): string;
    public function getType(): string;
    public function getVersion(): string;
    public function getDuration(): int;
    public function getMapID(): int;
}
