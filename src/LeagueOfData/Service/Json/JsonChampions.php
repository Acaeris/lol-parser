<?php

namespace LeagueOfData\Service\Json;

use LeagueOfData\Models\Champion;
use LeagueOfData\Service\Interfaces\ChampionService;
use LeagueOfData\Adapters\AdapterInterface;
use Psr\Log\LoggerInterface;

final class JsonChampions implements ChampionService
{
    private $source;
    private $log;
    private $champions;

    public function __construct(AdapterInterface $adapter, LoggerInterface $log)
    {
        $this->source = $adapter;
        $this->log = $log;
    }

    public function add(Champion $champion) {
        $this->champions[] = $champion;
    }

    public function findAll($version) {
        $response = $this->source->fetch('champion', [ 'version' => $version ]);
        $this->champions = [];
        foreach ($response->data as $champion) {
            $this->champions[] = $this->create($champion, $response->version);
        }
        return $this->champions;
    }

    public function find($id, $version)
    {
        $response = $this->source->fetch('champion', ['id' => $id, 'region' => 'euw', 'version' => $version]);
        $this->champions = [ $this->create($response, $version) ];
        return $this->champions;
    }

    public function store() {
        $this->log->error("Request to store data through JSON API not available.");
    }

    private function create($champion, $version)
    {
        return new Champion(
                $champion->id,
                $champion->name,
                $champion->title,
                [
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
                'spellBlockPerLevel' => $champion->stats->spellblockperlevel
                ],
                $version
            );
    }
}
