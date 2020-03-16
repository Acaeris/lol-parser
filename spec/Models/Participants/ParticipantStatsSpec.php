<?php

namespace spec\App\Models\Participants;

use PhpSpec\ObjectBehavior;

class ParticipantStatsSpec extends ObjectBehavior
{
    private $participantId = 1;
    private $physicalDamageDealt = 2;
    private $physicalDamageDealtToChampions = 3;
    private $magicDamageDealt = 4;
    private $magicDamageDealtToChampions = 5;
    private $trueDamageDealt = 6;
    private $trueDamageDealtToChampions = 7;
    private $totalDamageDealt = 8;
    private $totalDamageDealtToChampions = 9;
    private $damageDealtToTurrets = 10;
    private $damageDealtToObjectives = 11;
    private $largestCriticalStrike = 12;
    private $largestKillingSpree = 13;
    private $kills = 14;
    private $doubleKills = 15;
    private $tripleKills = 16;
    private $quadraKills = 17;
    private $pentaKills = 18;
    private $unrealKills = 19;
    private $largestMultiKill = 20;
    private $killingSprees = 21;
    private $assists = 22;
    private $physicalDamageTaken = 23;
    private $magicalDamageTaken = 24;
    private $trueDamageTaken = 25;
    private $totalDamageTaken = 26;
    private $damageSelfMitigated = 27;
    private $totalHeal = 28;
    private $totalUnitsHealed = 29;
    private $totalTimeCrowdControlDealt = 30;
    private $timeCCingOthers = 31;
    private $longestTimeSpentLiving = 32;
    private $neutralMinionsKilled = 33;
    private $neutralMinionsKilledTeamJungle = 34;
    private $neutralMinionsKilledEnemyJungle = 35;
    private $totalMinionsKilled = 36;
    private $combatPlayerScore = 37;
    private $altarsNeutralized = 38;
    private $altarsCaptured = 39;
    private $turretKills = 40;
    private $inhibitorKills = 41;
    private $teamObjective = 42;
    private $nodeNeutralize = 43;
    private $nodeNeutralizeAssist = 44;
    private $nodeCapture = 45;
    private $nodeCaptureAssist = 46;
    private $firstBloodAssist = true;
    private $firstBloodKill = false;
    private $firstTowerAssist = true;
    private $firstTowerKill = false;
    private $firstInhibitorAssist = true;
    private $firstInhibitorKill = false;
    private $objectivePlayerScore = 47;
    private $visionWardsBoughtInGame = 48;
    private $sightWardsBoughtInGame = 49;
    private $wardsPlaced = 50;
    private $wardsKilled = 51;
    private $visionScore = 52;
    private $totalPlayerScore = 53;
    private $totalScoreRank = 54;
    private $champLevel = 55;
    private $goldEarned = 56;
    private $goldSpent = 57;
    private $item0 = 58;
    private $item1 = 59;
    private $item2 = 60;
    private $item3 = 61;
    private $item4 = 62;
    private $item5 = 63;
    private $item6 = 64;
    private $deaths = 65;
    private $win = true;

    public function let()
    {
        $this->beConstructedWith(
            $this->participantId,
            $this->physicalDamageDealt,
            $this->physicalDamageDealtToChampions,
            $this->magicDamageDealt,
            $this->magicDamageDealtToChampions,
            $this->trueDamageDealt,
            $this->trueDamageDealtToChampions,
            $this->totalDamageDealt,
            $this->totalDamageDealtToChampions,
            $this->damageDealtToTurrets,
            $this->damageDealtToObjectives,
            $this->largestCriticalStrike,
            $this->largestKillingSpree,
            $this->kills,
            $this->doubleKills,
            $this->tripleKills,
            $this->quadraKills,
            $this->pentaKills,
            $this->unrealKills,
            $this->largestMultiKill,
            $this->killingSprees,
            $this->assists,
            $this->physicalDamageTaken,
            $this->magicalDamageTaken,
            $this->trueDamageTaken,
            $this->totalDamageTaken,
            $this->damageSelfMitigated,
            $this->totalHeal,
            $this->totalUnitsHealed,
            $this->totalTimeCrowdControlDealt,
            $this->timeCCingOthers,
            $this->longestTimeSpentLiving,
            $this->neutralMinionsKilled,
            $this->neutralMinionsKilledTeamJungle,
            $this->neutralMinionsKilledEnemyJungle,
            $this->totalMinionsKilled,
            $this->combatPlayerScore,
            $this->altarsNeutralized,
            $this->altarsCaptured,
            $this->turretKills,
            $this->inhibitorKills,
            $this->teamObjective,
            $this->nodeNeutralize,
            $this->nodeNeutralizeAssist,
            $this->nodeCapture,
            $this->nodeCaptureAssist,
            $this->firstBloodAssist,
            $this->firstBloodKill,
            $this->firstTowerAssist,
            $this->firstTowerKill,
            $this->firstInhibitorAssist,
            $this->firstInhibitorKill,
            $this->objectivePlayerScore,
            $this->visionWardsBoughtInGame,
            $this->sightWardsBoughtInGame,
            $this->wardsPlaced,
            $this->wardsKilled,
            $this->visionScore,
            $this->totalPlayerScore,
            $this->totalScoreRank,
            $this->champLevel,
            $this->goldEarned,
            $this->goldSpent,
            $this->item0,
            $this->item1,
            $this->item2,
            $this->item3,
            $this->item4,
            $this->item5,
            $this->item6,
            $this->deaths,
            $this->win
        );
    }

    public function it_should_have_a_participant_id()
    {
        $this->getParticipantId()->shouldReturn($this->participantId);
    }

    public function it_has_physical_damage_dealt_data()
    {
        $this->getPhysicalDamageDealt()->shouldReturn($this->physicalDamageDealt);
    }

    public function it_has_physical_damage_dealt_to_champions_data()
    {
        $this->getPhysicalDamageDealtToChampions()->shouldReturn($this->physicalDamageDealtToChampions);
    }

    public function it_has_magic_damage_dealt_data()
    {
        $this->getMagicDamageDealt()->shouldReturn($this->magicDamageDealt);
    }

    public function it_has_magic_damage_dealt_to_champions_data()
    {
        $this->getMagicDamageDealtToChampions()->shouldReturn($this->magicDamageDealtToChampions);
    }

    public function it_has_true_damage_dealt_data()
    {
        $this->getTrueDamageDealt()->shouldReturn($this->trueDamageDealt);
    }

    public function it_has_true_damage_dealt_to_champions_data()
    {
        $this->getTrueDamageDealtToChampions()->shouldReturn($this->trueDamageDealtToChampions);
    }

    public function it_has_total_damage_dealt_data()
    {
        $this->getTotalDamageDealt()->shouldReturn($this->totalDamageDealt);
    }

    public function it_has_total_damage_dealt_to_champions_data()
    {
        $this->getTotalDamageDealtToChampions()->shouldReturn($this->totalDamageDealtToChampions);
    }

    public function it_has_damage_dealt_to_turrets_data()
    {
        $this->getDamageDealtToTurrets()->shouldReturn($this->damageDealtToTurrets);
    }

    public function it_has_damage_dealt_to_objectives_data()
    {
        $this->getDamageDealtToObjectives()->shouldReturn($this->damageDealtToObjectives);
    }

    public function it_has_largest_critical_strike_data()
    {
        $this->getLargestCriticalStrike()->shouldReturn($this->largestCriticalStrike);
    }

    public function it_has_largest_killing_spree_data()
    {
        $this->getLargestKillingSpree()->shouldReturn($this->largestKillingSpree);
    }

    public function it_has_number_of_kills()
    {
        $this->getKills()->shouldReturn($this->kills);
    }

    public function it_has_number_of_double_kills()
    {
        $this->getDoubleKills()->shouldReturn($this->doubleKills);
    }

    public function it_has_number_of_triple_kills()
    {
        $this->getTripleKills()->shouldReturn($this->tripleKills);
    }

    public function it_has_number_of_quadra_kills()
    {
        $this->getQuadraKills()->shouldReturn($this->quadraKills);
    }

    public function it_has_number_of_penta_kills()
    {
        $this->getPentaKills()->shouldReturn($this->pentaKills);
    }

    public function it_has_number_of_unreal_kills()
    {
        $this->getUnrealKills()->shouldReturn($this->unrealKills);
    }

    public function it_has_largest_multikill_data()
    {
        $this->getLargestMultiKill()->shouldReturn($this->largestMultiKill);
    }

    public function it_has_number_of_killing_sprees()
    {
        $this->getKillingSprees()->shouldReturn($this->killingSprees);
    }

    public function it_has_number_of_assists()
    {
        $this->getAssists()->shouldReturn($this->assists);
    }

    public function it_has_physical_damage_taken_data()
    {
        $this->getPhysicalDamageTaken()->shouldReturn($this->physicalDamageTaken);
    }

    public function it_has_magical_damage_taken_data()
    {
        $this->getMagicalDamageTaken()->shouldReturn($this->magicalDamageTaken);
    }

    public function it_has_true_damage_taken_data()
    {
        $this->getTrueDamageTaken()->shouldReturn($this->trueDamageTaken);
    }

    public function it_has_total_damage_taken_data()
    {
        $this->getTotalDamageTaken()->shouldReturn($this->totalDamageTaken);
    }

    public function it_has_damage_self_mitigated_data()
    {
        $this->getDamageSelfMitigated()->shouldReturn($this->damageSelfMitigated);
    }

    public function it_has_total_healed_data()
    {
        $this->getTotalHeal()->shouldReturn($this->totalHeal);
    }

    public function it_has_total_units_healed_data()
    {
        $this->getTotalUnitsHealed()->shouldReturn($this->totalUnitsHealed);
    }

    public function it_has_total_time_crowd_control_dealt_data()
    {
        $this->getTotalTimeCrowdControlDealt()->shouldReturn($this->totalTimeCrowdControlDealt);
    }

    public function it_has_time_ccing_others_data()
    {
        $this->getTimeCCingOthers()->shouldReturn($this->timeCCingOthers);
    }

    public function it_has_longest_time_spent_living_data()
    {
        $this->getLongestTimeSpentLiving()->shouldReturn($this->longestTimeSpentLiving);
    }

    public function it_has_number_of_neutral_minions_killed()
    {
        $this->getNeutralMinionsKilled()->shouldReturn($this->neutralMinionsKilled);
    }

    public function it_has_number_of_neutral_minions_killed_in_the_team_jungle()
    {
        $this->getNeutralMinionsKilledTeamJungle()->shouldReturn($this->neutralMinionsKilledTeamJungle);
    }

    public function it_has_number_of_neutral_minions_killed_in_the_enemy_jungle()
    {
        $this->getNeutralMinionsKilledEnemyJungle()->shouldReturn($this->neutralMinionsKilledEnemyJungle);
    }

    public function it_has_total_number_of_minions_killed()
    {
        $this->getTotalMinionsKilled()->shouldReturn($this->totalMinionsKilled);
    }

    public function it_has_combat_player_score()
    {
        $this->getCombatPlayerScore()->shouldReturn($this->combatPlayerScore);
    }

    public function it_has_number_of_altars_neutralized()
    {
        $this->getAltarsNeutralized()->shouldReturn($this->altarsNeutralized);
    }

    public function it_has_number_of_altars_captured()
    {
        $this->getAltarsCaptured()->shouldReturn($this->altarsCaptured);
    }

    public function it_has_number_of_turret_kills()
    {
        $this->getTurretKills()->shouldReturn($this->turretKills);
    }

    public function it_has_number_of_inhibitor_kills()
    {
        $this->getInhibitorKills()->shouldReturn($this->inhibitorKills);
    }

    public function it_has_team_objective_score()
    {
        $this->getTeamObjective()->shouldReturn($this->teamObjective);
    }

    public function it_has_number_of_nodes_neutralized()
    {
        $this->getNodeNeutralize()->shouldReturn($this->nodeNeutralize);
    }

    public function it_has_number_of_nodes_assisted_in_neutralizing()
    {
        $this->getNodeNeutralizeAssist()->shouldReturn($this->nodeNeutralizeAssist);
    }

    public function it_has_number_of_nodes_captured()
    {
        $this->getNodeCapture()->shouldReturn($this->nodeCapture);
    }

    public function it_has_number_of_nodes_assisted_in_capturing()
    {
        $this->getNodeCaptureAssist()->shouldReturn($this->nodeCaptureAssist);
    }

    public function it_has_if_assisted_in_first_blood()
    {
        $this->hadFirstBloodAssist()->shouldReturn($this->firstBloodAssist);
    }

    public function it_has_if_scored_first_blood()
    {
        $this->hadFirstBloodKill()->shouldReturn($this->firstBloodKill);
    }

    public function it_has_if_assisted_in_first_tower_kill()
    {
        $this->hadFirstTowerAssist()->shouldReturn($this->firstTowerAssist);
    }

    public function it_has_if_scored_first_tower_kill()
    {
        $this->hadFirstTowerKill()->shouldReturn($this->firstTowerKill);
    }

    public function it_has_if_assisted_in_first_inhibitor_kill()
    {
        $this->hadFirstInhibitorAssist()->shouldReturn($this->firstInhibitorAssist);
    }

    public function it_has_if_scored_first_inhibitor_kill()
    {
        $this->hadFirstInhibitorKill()->shouldReturn($this->firstInhibitorKill);
    }

    public function it_has_player_objective_score()
    {
        $this->getObjectivePlayerScore()->shouldReturn($this->objectivePlayerScore);
    }

    public function it_has_number_of_vision_wards_bought()
    {
        $this->getVisionWardsBoughtInGame()->shouldReturn($this->visionWardsBoughtInGame);
    }

    public function it_has_number_of_sight_wards_bought()
    {
        $this->getSightWardsBoughtInGame()->shouldReturn($this->sightWardsBoughtInGame);
    }

    public function it_has_number_of_wards_placed()
    {
        $this->getWardsPlaced()->shouldReturn($this->wardsPlaced);
    }

    public function it_has_number_of_wards_killed()
    {
        $this->getWardsKilled()->shouldReturn($this->wardsKilled);
    }

    public function it_has_player_vision_score()
    {
        $this->getVisionScore()->shouldReturn($this->visionScore);
    }

    public function it_has_total_player_score()
    {
        $this->getTotalPlayerScore()->shouldReturn($this->totalPlayerScore);
    }

    public function it_has_player_score_rank()
    {
        $this->getTotalScoreRank()->shouldReturn($this->totalScoreRank);
    }

    public function it_has_champion_level()
    {
        $this->getChampLevel()->shouldReturn($this->champLevel);
    }

    public function it_has_amount_of_gold_earned()
    {
        $this->getGoldEarned()->shouldReturn($this->goldEarned);
    }

    public function it_has_amount_of_gold_spent()
    {
        $this->getGoldSpent()->shouldReturn($this->goldSpent);
    }

    public function it_has_item_data()
    {
        $this->getItem(0)->shouldReturn($this->item0);
        $this->getItem(1)->shouldReturn($this->item1);
        $this->getItem(2)->shouldReturn($this->item2);
        $this->getItem(3)->shouldReturn($this->item3);
        $this->getItem(4)->shouldReturn($this->item4);
        $this->getItem(5)->shouldReturn($this->item5);
        $this->getItem(6)->shouldReturn($this->item6);
    }

    public function it_has_number_of_deaths()
    {
        $this->getDeaths()->shouldReturn($this->deaths);
    }

    public function it_has_if_game_was_won()
    {
        $this->isWin()->shouldReturn($this->win);
    }
}
