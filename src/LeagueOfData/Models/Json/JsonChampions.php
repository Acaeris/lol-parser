<?php

namespace LeagueOfData\Models\Json;

use LeagueOfData\Models\Champion;
use LeagueOfData\Models\Champions;
use LeagueOfData\Adapters\AdapterInterface;

final class JsonChampions implements Champions
{
    private $source;

    public function __construct(AdapterInterface $adapter)
    {
        $this->source = $adapter;
    }

    public function collectAll($version) {
        $response = $this->source->fetch('champion', [ 'version' => $version ]);
        $champions = [];
        foreach ($response->data as $champion) {
            $champions[] = $this->create($champion, $response->version);
        }
        return $champions;
    }

    public function collect($id, $version)
    {
        $response = $this->source->fetch('champion', ['id' => $id, 'region' => 'euw', 'version' => $version]);
        return $this->create($response, $version);
    }

    private function create($champion, $version)
    {
        return new Champion([
                'id' => $champion->id,
                'name' => $champion->name,
                'title' => $champion->title,
                'attackRange' => $champion->stats->attackrange,
                'moveSpeed' => $champion->stats->movespeed,
                'hp' => $champion->stats->hp,
                'hpPerLevel' => $champion->stats->hpperlevel,
                'hpRegen' => $champion->stats->hpregen,
                'hpRegenPerLevel' => $champion->stats->hpregenperlevel,
                'mp' => $champion->stats->mp,
                'mpPerLevel' => $champion->stats->mpperlevel,
                'mpRegen' => $champion->stats->mpregen,
                'mpRegenPerLevel' => $champion->stats->mpregenperlevel,
                'attackDamage' => $champion->stats->attackdamage,
                'attackDamagePerLevel' => $champion->stats->attackdamageperlevel,
                'attackSpeedOffset' => $champion->stats->attackspeedoffset,
                'attackSpeedPerLevel' => $champion->stats->attackspeedperlevel,
                'crit' => $champion->stats->crit,
                'critPerLevel' => $champion->stats->critperlevel,
                'armor' => $champion->stats->armor,
                'armorPerLevel' => $champion->stats->armorperlevel,
                'spellBlock' => $champion->stats->spellblock,
                'spellBlockPerLevel' => $champion->stats->spellblockperlevel,
                'version' => $version
            ]);
    }
}
