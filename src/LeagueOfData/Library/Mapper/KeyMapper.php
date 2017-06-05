<?php

namespace LeagueOfData\Library\Mapper;

/**
 * Temporary solution to inconsistent keys.
 */
class KeyMapper {
    static $spellKeys = [
        'Disintegrate' => 'Q',
        'Incinerate' => 'W',
        'MoltenShield' => 'E',
        'InfernalGuardian' => 'R',
        'OlafAxeThrowCast' => 'Q',
        'OlafFrenziedStrikes' => 'W',
        'OlafRecklessStrike' => 'E',
        'OlafRagnarok' => 'R',
        'GalioQ' => 'Q',
        'GalioW' => 'W',
        'GalioE' => 'E',
        'GalioR' => 'R'
    ];

    public static function getKeyForSpell(string $spell) : string
    {
        if (strlen($spell) === 1) {
            return $spell;
        }
        return self::$spellKeys[$spell];
    }
}
