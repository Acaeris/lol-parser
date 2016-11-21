<?php

namespace LeagueOfData\Models;

final class ChampionResource
{
    const RESOURCE_HEALTH = 'hp';
    const RESOURCE_MANA = 'mp';

    private $type;
    private $baseValue;
    private $perLevel;
    private $regen;
    private $regenPerLevel;

    public function __construct($type, $baseValue, $perLevel, $regen, $regenPerLevel)
    {
        $this->type = $type;
        $this->baseValue = $baseValue;
        $this->perLevel = $perLevel;
        $this->regen = $regen;
        $this->regenPerLevel = $regenPerLevel;
    }

    public function toArray()
    {
        if ($this->type == self::RESOURCE_HEALTH) {
            $data = [
                'hp' => $this->baseValue,
                'hpPerLevel' => $this->perLevel,
                'hpRegen' => $this->regen,
                'hpRegenPerLevel' => $this->regenPerLevel
            ];
        } else {
            $data = [
                'resourceType' => $this->type,
                'mp' => $this->baseValue,
                'mpPerLevel' => $this->perLevel,
                'mpRegen' => $this->regen,
                'mpRegenPerLevel' => $this->regenPerLevel
            ];
        }
        return $data;
    }
}