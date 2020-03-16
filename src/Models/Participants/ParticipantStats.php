<?php

namespace App\Models\Participants;

class ParticipantStats
{
    /* @var int Participant ID */
    private $participantId;

    /* @var int Physical damage dealt */
    private $physicalDamageDealt;

    /* @var int Physical damage dealt to champions */
    private $physicalDamageDealtToChampions;

    /* @var int Magic damage dealt */
    private $magicDamageDealt;

    /* @var int Magic damage dealt to champions */
    private $magicDamageDealtToChampions;

    /* @var int True damage dealt */
    private $trueDamageDealt;

    /* @var int True damage dealt to champions */
    private $trueDamageDealtToChampions;

    /* @var int Total damage dealt */
    private $totalDamageDealt;

    /* @var int Total damage dealt to champions */
    private $totalDamageDealtToChampions;

    /* @var int Damage dealt to turrets */
    private $damageDealtToTurrets;

    /* @var int Damage dealt to objectives */
    private $damageDealtToObjectives;

    /* @var int Largest critical strike */
    private $largestCriticalStrike;

    /* @var int Largest killing spree */
    private $largestKillingSpree;

    /* @var int Kills */
    private $kills;

    /* @var int Double kills */
    private $doubleKills;

    /* @var int Triple kills */
    private $tripleKills;

    /* @var int Quadra kills */
    private $quadraKills;

    /* @var int Penta kills */
    private $pentaKills;

    /* @var int Unreal kills */
    private $unrealKills;

    /* @var int Largest multi-kill */
    private $largestMultiKill;

    /* @var int Killing sprees */
    private $killingSprees;

    /* @var int Assists */
    private $assists;

    /* @var int Physical damage taken */
    private $physicalDamageTaken;

    /* @var int Magical damage taken */
    private $magicalDamageTaken;

    /* @var int True damage taken */
    private $trueDamageTaken;

    /* @var int Total damage taken */
    private $totalDamageTaken;

    /* @var int Damage self mitigated */
    private $damageSelfMitigated;

    /* @var int Total heal */
    private $totalHeal;

    /* @var int Total units healed */
    private $totalUnitsHealed;

    /* @var int Total time crowd control dealt */
    private $totalTimeCrowdControlDealt;

    /* @var int Time crowd controlling others */
    private $timeCCingOthers;

    /* @var int Longest time spent living */
    private $longestTimeSpentLiving;

    /* @var int Neutral minions killed */
    private $neutralMinionsKilled;

    /* @var int Neutral minions killed in the team jungle */
    private $neutralMinionsKilledTeamJungle;

    /* @var int Neutral minions killed in the enemy jungle */
    private $neutralMinionsKilledEnemyJungle;

    /* @var int Total minions killed */
    private $totalMinionsKilled;

    /* @var int Combat player score */
    private $combatPlayerScore;

    /* @var int Altars neutralized */
    private $altarsNeutralized;

    /* @var int Altars captured */
    private $altarsCaptured;

    /* @var int Turret kills */
    private $turretKills;

    /* @var int Inhibitor kills */
    private $inhibitorKills;

    /* @var int Team objective */
    private $teamObjective;

    /* @var int Node neutralize */
    private $nodeNeutralize;

    /* @var int Node neutralize assists */
    private $nodeNeutralizeAssist;

    /* @var int Node captures */
    private $nodeCapture;

    /* @var int Node capture assists */
    private $nodeCaptureAssist;

    /* @var bool First blood assist */
    private $firstBloodAssist;

    /* @var bool First blood kill */
    private $firstBloodKill;

    /* @var bool First tower assist */
    private $firstTowerAssist;

    /* @var bool First tower kill */
    private $firstTowerKill;

    /* @var bool First inhibitor assist */
    private $firstInhibitorAssist;

    /* @var bool First inhibitor kill */
    private $firstInhibitorKill;

    /* @var int Objective player score */
    private $objectivePlayerScore;

    /* @var int Vision wards bought in game */
    private $visionWardsBoughtInGame;

    /* @var int Sight wards bought in game */
    private $sightWardsBoughtInGame;

    /* @var int Wards placed */
    private $wardsPlaced;

    /* @var int Wards killed */
    private $wardsKilled;

    /* @var int Vision score */
    private $visionScore;

    /* @var int Total player score */
    private $totalPlayerScore;

    /* @var int Total score rank */
    private $totalScoreRank;

    /* @var int Champion level */
    private $champLevel;

    /* @var int Gold earned */
    private $goldEarned;

    /* @var int Gold spent */
    private $goldSpent;

    // These are kept separate for the purpose of JsonMapper at this time.
    /* @var int Item slot 0 */
    private $item0;

    /* @var int Item slot 1 */
    private $item1;

    /* @var int Item slot 2 */
    private $item2;

    /* @var int Item slot 3 */
    private $item3;

    /* @var int Item slot 4 */
    private $item4;

    /* @var int Item slot 5 */
    private $item5;

    /* @var int Item slot 6 */
    private $item6;

    /* @var int Deaths */
    private $deaths;

    /* @var bool Win */
    private $win;

    /**
     * @param int  $participantId                   Participant ID
     * @param int  $physicalDamageDealt             Physical damage dealt
     * @param int  $physicalDamageDealtToChampions  Physical damage dealt to champions
     * @param int  $magicDamageDealt                Magic damage dealt
     * @param int  $magicDamageDealtToChampions     Magic damage dealt to champions
     * @param int  $trueDamageDealt                 True damage dealt
     * @param int  $trueDamageDealtToChampions      True damage dealt to champions
     * @param int  $totalDamageDealt                Total damage dealt
     * @param int  $totalDamageDealtToChampions     Total damage dealt to champions
     * @param int  $damageDealtToTurrets            Damage dealt to turrets
     * @param int  $damageDealtToObjectives         Damage dealt to objectives
     * @param int  $largestCriticalStrike           Largest critical strike
     * @param int  $largestKillingSpree             Largest killing spree
     * @param int  $kills                           Kills
     * @param int  $doubleKills                     Double kills
     * @param int  $tripleKills                     Triple kills
     * @param int  $quadraKills                     Quadra kills
     * @param int  $pentaKills                      Penta kills
     * @param int  $unrealKills                     Unreal kills
     * @param int  $largestMultiKill                Largest multikill
     * @param int  $killingSprees                   Killing sprees
     * @param int  $assists                         Assists
     * @param int  $physicalDamageTaken             Physical damage taken
     * @param int  $magicalDamageTaken              Magical damage taken
     * @param int  $trueDamageTaken                 True damage taken
     * @param int  $totalDamageTaken                Total damage taken
     * @param int  $damageSelfMitigated             Damage self mitigated
     * @param int  $totalHeal                       Total heal
     * @param int  $totalUnitsHealed                Total unit healed
     * @param int  $totalTimeCrowdControlDealt      Total time crowd control dealt
     * @param int  $timeCCingOthers                 Time crowd controlling others
     * @param int  $longestTimeSpentLiving          Longest time spent living
     * @param int  $neutralMinionsKilled            Neurtal minions killed
     * @param int  $neutralMinionsKilledTeamJungle  Neutral minions killed in team jungle
     * @param int  $neutralMinionsKilledEnemyJungle Neutral minoins killed in enemy jungle
     * @param int  $totalMinionsKilled              Total minions killed
     * @param int  $combatPlayerScore               Combat player score
     * @param int  $altarsNeutralized               Altars neutralized
     * @param int  $altarsCaptured                  Altars captured
     * @param int  $turretKills                     Turret kills
     * @param int  $inhibitorKills                  Inhibitior kills
     * @param int  $teamObjective                   Team objective
     * @param int  $nodeNeutralize                  Node neutralize
     * @param int  $nodeNeutralizeAssist            Node neutralize assist
     * @param int  $nodeCapture                     Node capture
     * @param int  $nodeCaptureAssist               Node capture assist
     * @param bool $firstBloodAssist                First blood assist
     * @param bool $firstBloodKill                  First blood kill
     * @param bool $firstTowerAssist                First blood assist
     * @param bool $firstTowerKill                  First tower kill
     * @param bool $firstInhibitorAssist            First inhibitor assist
     * @param bool $firstInhibitorKill              First inhibitor kill
     * @param int  $objectivePlayerScore            Objective player score
     * @param int  $visionWardsBoughtInGame         Vision wards bought in game
     * @param int  $sightWardsBoughtInGame          Sight wards bought in game
     * @param int  $wardsPlaced                     Wards placed
     * @param int  $wardsKilled                     Wards killed
     * @param int  $visionScore                     Vision score
     * @param int  $totalPlayerScore                Total player score
     * @param int  $totalScoreRank                  Total score rank
     * @param int  $champLevel                      Champion level
     * @param int  $goldEarned                      Gold earned
     * @param int  $goldSpent                       Gold spent
     * @param int  $item0                           Item slot 0
     * @param int  $item1                           Item slot 1
     * @param int  $item2                           Item slot 2
     * @param int  $item3                           Item slot 3
     * @param int  $item4                           Item slot 4
     * @param int  $item5                           Item slot 5
     * @param int  $item6                           Item slot 6
     * @param int  $deaths                          Deaths
     * @param bool $win                             Win
     */
    public function __construct(
        $participantId,
        $physicalDamageDealt,
        $physicalDamageDealtToChampions,
        $magicDamageDealt,
        $magicDamageDealtToChampions,
        $trueDamageDealt,
        $trueDamageDealtToChampions,
        $totalDamageDealt,
        $totalDamageDealtToChampions,
        $damageDealtToTurrets,
        $damageDealtToObjectives,
        $largestCriticalStrike,
        $largestKillingSpree,
        $kills,
        $doubleKills,
        $tripleKills,
        $quadraKills,
        $pentaKills,
        $unrealKills,
        $largestMultiKill,
        $killingSprees,
        $assists,
        $physicalDamageTaken,
        $magicalDamageTaken,
        $trueDamageTaken,
        $totalDamageTaken,
        $damageSelfMitigated,
        $totalHeal,
        $totalUnitsHealed,
        $totalTimeCrowdControlDealt,
        $timeCCingOthers,
        $longestTimeSpentLiving,
        $neutralMinionsKilled,
        $neutralMinionsKilledTeamJungle,
        $neutralMinionsKilledEnemyJungle,
        $totalMinionsKilled,
        $combatPlayerScore,
        $altarsNeutralized,
        $altarsCaptured,
        $turretKills,
        $inhibitorKills,
        $teamObjective,
        $nodeNeutralize,
        $nodeNeutralizeAssist,
        $nodeCapture,
        $nodeCaptureAssist,
        $firstBloodAssist,
        $firstBloodKill,
        $firstTowerAssist,
        $firstTowerKill,
        $firstInhibitorAssist,
        $firstInhibitorKill,
        $objectivePlayerScore,
        $visionWardsBoughtInGame,
        $sightWardsBoughtInGame,
        $wardsPlaced,
        $wardsKilled,
        $visionScore,
        $totalPlayerScore,
        $totalScoreRank,
        $champLevel,
        $goldEarned,
        $goldSpent,
        $item0,
        $item1,
        $item2,
        $item3,
        $item4,
        $item5,
        $item6,
        $deaths,
        $win
    ) {
        $this->participantId = $participantId;
        $this->physicalDamageDealt = $physicalDamageDealt;
        $this->physicalDamageDealtToChampions = $physicalDamageDealtToChampions;
        $this->magicDamageDealt = $magicDamageDealt;
        $this->magicDamageDealtToChampions = $magicDamageDealtToChampions;
        $this->trueDamageDealt = $trueDamageDealt;
        $this->trueDamageDealtToChampions = $trueDamageDealtToChampions;
        $this->totalDamageDealt = $totalDamageDealt;
        $this->totalDamageDealtToChampions = $totalDamageDealtToChampions;
        $this->damageDealtToTurrets = $damageDealtToTurrets;
        $this->damageDealtToObjectives = $damageDealtToObjectives;
        $this->largestCriticalStrike = $largestCriticalStrike;
        $this->largestKillingSpree = $largestKillingSpree;
        $this->kills = $kills;
        $this->doubleKills = $doubleKills;
        $this->tripleKills = $tripleKills;
        $this->quadraKills = $quadraKills;
        $this->pentaKills = $pentaKills;
        $this->unrealKills = $unrealKills;
        $this->largestMultiKill = $largestMultiKill;
        $this->killingSprees = $killingSprees;
        $this->assists = $assists;
        $this->physicalDamageTaken = $physicalDamageTaken;
        $this->magicalDamageTaken = $magicalDamageTaken;
        $this->trueDamageTaken = $trueDamageTaken;
        $this->totalDamageTaken = $totalDamageTaken;
        $this->damageSelfMitigated = $damageSelfMitigated;
        $this->totalHeal = $totalHeal;
        $this->totalUnitsHealed = $totalUnitsHealed;
        $this->totalTimeCrowdControlDealt = $totalTimeCrowdControlDealt;
        $this->timeCCingOthers = $timeCCingOthers;
        $this->longestTimeSpentLiving = $longestTimeSpentLiving;
        $this->neutralMinionsKilled = $neutralMinionsKilled;
        $this->neutralMinionsKilledTeamJungle = $neutralMinionsKilledTeamJungle;
        $this->neutralMinionsKilledEnemyJungle = $neutralMinionsKilledEnemyJungle;
        $this->totalMinionsKilled = $totalMinionsKilled;
        $this->combatPlayerScore = $combatPlayerScore;
        $this->altarsNeutralized = $altarsNeutralized;
        $this->altarsCaptured = $altarsCaptured;
        $this->turretKills = $turretKills;
        $this->inhibitorKills = $inhibitorKills;
        $this->teamObjective = $teamObjective;
        $this->nodeNeutralize = $nodeNeutralize;
        $this->nodeNeutralizeAssist = $nodeNeutralizeAssist;
        $this->nodeCapture = $nodeCapture;
        $this->nodeCaptureAssist = $nodeCaptureAssist;
        $this->firstBloodAssist = $firstBloodAssist;
        $this->firstBloodKill = $firstBloodKill;
        $this->firstTowerAssist = $firstTowerAssist;
        $this->firstTowerKill = $firstTowerKill;
        $this->firstInhibitorAssist = $firstInhibitorAssist;
        $this->firstInhibitorKill = $firstInhibitorKill;
        $this->objectivePlayerScore = $objectivePlayerScore;
        $this->visionWardsBoughtInGame = $visionWardsBoughtInGame;
        $this->sightWardsBoughtInGame = $sightWardsBoughtInGame;
        $this->wardsPlaced = $wardsPlaced;
        $this->wardsKilled = $wardsKilled;
        $this->visionScore = $visionScore;
        $this->totalPlayerScore = $totalPlayerScore;
        $this->totalScoreRank = $totalScoreRank;
        $this->champLevel = $champLevel;
        $this->goldEarned = $goldEarned;
        $this->goldSpent = $goldSpent;
        $this->item0 = $item0;
        $this->item1 = $item1;
        $this->item2 = $item2;
        $this->item3 = $item3;
        $this->item4 = $item4;
        $this->item5 = $item5;
        $this->item6 = $item6;
        $this->deaths = $deaths;
        $this->win = $win;
    }

    /**
     * @return int Participant ID
     */
    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    /**
     * @return int Physical damage dealt
     */
    public function getPhysicalDamageDealt(): int
    {
        return $this->physicalDamageDealt;
    }

    /**
     * @return int Physical damage dealt to champions
     */
    public function getPhysicalDamageDealtToChampions(): int
    {
        return $this->physicalDamageDealtToChampions;
    }

    /**
     * @return int Magic damage dealt
     */
    public function getMagicDamageDealt(): int
    {
        return $this->magicDamageDealt;
    }

    /**
     * @return int Magic damage dealt to champions
     */
    public function getMagicDamageDealtToChampions(): int
    {
        return $this->magicDamageDealtToChampions;
    }

    /**
     * @return int True damage dealt
     */
    public function getTrueDamageDealt(): int
    {
        return $this->trueDamageDealt;
    }

    /**
     * @return int True damage dealt to champions
     */
    public function getTrueDamageDealtToChampions(): int
    {
        return $this->trueDamageDealtToChampions;
    }

    /**
     * @return int Total damage dealt
     */
    public function getTotalDamageDealt(): int
    {
        return $this->totalDamageDealt;
    }

    /**
     * @return int Total damage dealt to champions
     */
    public function getTotalDamageDealtToChampions(): int
    {
        return $this->totalDamageDealtToChampions;
    }

    /**
     * @return int Damage dealt to turrets
     */
    public function getDamageDealtToTurrets(): int
    {
        return $this->damageDealtToTurrets;
    }

    /**
     * @return int Damage dealt to objectives
     */
    public function getDamageDealtToObjectives(): int
    {
        return $this->damageDealtToObjectives;
    }

    /**
     * @return int Largest critical strike dealt
     */
    public function getLargestCriticalStrike(): int
    {
        return $this->largestCriticalStrike;
    }

    /**
     * @return int Largest killing spree
     */
    public function getLargestKillingSpree(): int
    {
        return $this->largestKillingSpree;
    }

    /**
     * @return int Number of kills
     */
    public function getKills(): int
    {
        return $this->kills;
    }

    /**
     * @return int Number of double kills
     */
    public function getDoubleKills(): int
    {
        return $this->doubleKills;
    }

    /**
     * @return int Number of triple kills
     */
    public function getTripleKills(): int
    {
        return $this->tripleKills;
    }

    /**
     * @return int Number of quadra kills
     */
    public function getQuadraKills(): int
    {
        return $this->quadraKills;
    }

    /**
     * @return int Number of penta kills
     */
    public function getPentaKills(): int
    {
        return $this->pentaKills;
    }

    /**
     * @return int Number of unreal kills
     */
    public function getUnrealKills(): int
    {
        return $this->unrealKills;
    }

    /**
     * @return int Largest multi kill
     */
    public function getLargestMultiKill(): int
    {
        return $this->largestMultiKill;
    }

    /**
     * @return int Number of killing sprees
     */
    public function getKillingSprees(): int
    {
        return $this->killingSprees;
    }

    /**
     * @return int Number of assists
     */
    public function getAssists(): int
    {
        return $this->assists;
    }

    /**
     * @return int Amount of physical damage taken
     */
    public function getPhysicalDamageTaken(): int
    {
        return $this->physicalDamageTaken;
    }

    /**
     * @return int Amount of magical damage taken
     */
    public function getMagicalDamageTaken(): int
    {
        return $this->magicalDamageTaken;
    }

    /**
     * @return int Amount of true damage taken
     */
    public function getTrueDamageTaken(): int
    {
        return $this->trueDamageTaken;
    }

    /**
     * @return int Total amount of damage taken
     */
    public function getTotalDamageTaken(): int
    {
        return $this->totalDamageTaken;
    }

    /**
     * @return int Amount of damage player has prevented themselves from taking
     */
    public function getDamageSelfMitigated(): int
    {
        return $this->damageSelfMitigated;
    }

    /**
     * @return int Total amount of damage healed
     */
    public function getTotalHeal(): int
    {
        return $this->totalHeal;
    }

    /**
     * @return int Total number of units healed
     */
    public function getTotalUnitsHealed(): int
    {
        return $this->totalUnitsHealed;
    }

    /**
     * @return int Total time of crowd control effects inflicted
     */
    public function getTotalTimeCrowdControlDealt(): int
    {
        return $this->totalTimeCrowdControlDealt;
    }

    /**
     * @return int Total time of crowd control effects inflicted on others
     */
    public function getTimeCCingOthers(): int
    {
        return $this->timeCCingOthers;
    }

    /**
     * @return int Longest time spent alive
     */
    public function getLongestTimeSpentLiving(): int
    {
        return $this->longestTimeSpentLiving;
    }

    /**
     * @return int Number of neutral minions killed
     */
    public function getNeutralMinionsKilled(): int
    {
        return $this->neutralMinionsKilled;
    }

    /**
     * @return int Number of neutral minions killed in own jungle
     */
    public function getNeutralMinionsKilledTeamJungle(): int
    {
        return $this->neutralMinionsKilledTeamJungle;
    }

    /**
     * @return int Number of neutral minions killed in enemy jungle
     */
    public function getNeutralMinionsKilledEnemyJungle(): int
    {
        return $this->neutralMinionsKilledEnemyJungle;
    }

    /**
     * @return int Total number of minions killed
     */
    public function getTotalMinionsKilled(): int
    {
        return $this->totalMinionsKilled;
    }

    /**
     * @return int PLayer's combat score for the game
     */
    public function getCombatPlayerScore(): int
    {
        return $this->combatPlayerScore;
    }

    /**
     * @return int Number of altars neutralized in Twisted Treeline (no longer available)
     * @todo Do we care about the few TT entries we'll get?
     */
    public function getAltarsNeutralized(): int
    {
        return $this->altarsNeutralized;
    }

    /**
     * @return int Number of altars captured in Twisted Treeline (no longer available)
     * @todo Do we care about the few TT entries we'll get?
     */
    public function getAltarsCaptured(): int
    {
        return $this->altarsCaptured;
    }

    /**
     * @return int Number of turret kills
     */
    public function getTurretKills(): int
    {
        return $this->turretKills;
    }

    /**
     * @return int Number of inhibitor kills
     */
    public function getInhibitorKills(): int
    {
        return $this->inhibitorKills;
    }

    /**
     * @return int Team objective score (Possibly for Dominion)
     * @todo Check if this is Dominion exclusive
     */
    public function getTeamObjective(): int
    {
        return $this->teamObjective;
    }

    /**
     * @return int Number of nodes neutralized (Dominion, no longer available)
     * @todo Do we need to keep Dominion stats?
     */
    public function getNodeNeutralize(): int
    {
        return $this->nodeNeutralize;
    }

    /**
     * @return int Number of nodes assisted neutralizing (Dominion, no longer available)
     * @todo Do we need to keep Dominion stats?
     */
    public function getNodeNeutralizeAssist(): int
    {
        return $this->nodeNeutralizeAssist;
    }

    /**
     * @return int Number of nodes captured (Dominion, no longer available)
     * @todo Do we need to keep Dominion stats?
     */
    public function getNodeCapture(): int
    {
        return $this->nodeCapture;
    }

    /**
     * @return int Number of nodes assisted in capturing (Dominion, no longer available)
     * @todo Do we need to keep Dominion stats?
     */
    public function getNodeCaptureAssist(): int
    {
        return $this->nodeCaptureAssist;
    }

    /**
     * @return bool Assisted in first blood?
     */
    public function hadFirstBloodAssist(): bool
    {
        return $this->firstBloodAssist;
    }

    /**
     * @return bool First blood?
     */
    public function hadFirstBloodKill(): bool
    {
        return $this->firstBloodKill;
    }

    /**
     * @return bool Assisted in first tower kill?
     */
    public function hadFirstTowerAssist(): bool
    {
        return $this->firstTowerAssist;
    }

    /**
     * @return bool First tower kill?
     */
    public function hadFirstTowerKill(): bool
    {
        return $this->firstTowerKill;
    }

    /**
     * @return bool Assisted in first inhibitor kill?
     */
    public function hadFirstInhibitorAssist(): bool
    {
        return $this->firstInhibitorAssist;
    }

    /**
     * @return bool First inhibitor kill?
     */
    public function hadFirstInhibitorKill(): bool
    {
        return $this->firstInhibitorKill;
    }

    /**
     * @return int Player objective score
     * @todo Check if this is only relevant to Dominion
     */
    public function getObjectivePlayerScore(): int
    {
        return $this->objectivePlayerScore;
    }

    /**
     * @return int Number of vision wards purchased
     */
    public function getVisionWardsBoughtInGame(): int
    {
        return $this->visionWardsBoughtInGame;
    }

    /**
     * @return int Number of sight wards purchased
     */
    public function getSightWardsBoughtInGame(): int
    {
        return $this->sightWardsBoughtInGame;
    }

    /**
     * @return int Number of wards placed
     */
    public function getWardsPlaced(): int
    {
        return $this->wardsPlaced;
    }

    /**
     * @return int Number of wards killed
     */
    public function getWardsKilled(): int
    {
        return $this->wardsKilled;
    }

    /**
     * @return int Vision score
     */
    public function getVisionScore(): int
    {
        return $this->visionScore;
    }

    /**
     * @return int Player effectiveness score
     */
    public function getTotalPlayerScore(): int
    {
        return $this->totalPlayerScore;
    }

    /**
     * @return int Player effectiveness rank
     */
    public function getTotalScoreRank(): int
    {
        return $this->totalScoreRank;
    }

    /**
     * @return int Champion level
     */
    public function getChampLevel(): int
    {
        return $this->champLevel;
    }

    /**
     * @return int Gold earned
     */
    public function getGoldEarned(): int
    {
        return $this->goldEarned;
    }

    /**
     * @return int Gold spent
     */
    public function getGoldSpent(): int
    {
        return $this->goldSpent;
    }

    /**
     * @param int $slotId Slot ID
     * @return int Item ID in provided slot
     */
    public function getItem(int $slotId): int
    {
        return $this->{'item' . $slotId};
    }

    /**
     * @return int Number of deaths
     */
    public function getDeaths()
    {
        return $this->deaths;
    }

    /**
     * @return bool Did the player win the game?
     */
    public function isWin(): bool
    {
        return $this->win;
    }

    
}
