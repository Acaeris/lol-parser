<?php

namespace App\Repositories;

use Psr\Http\Message\ResponseInterface;
use App\Models\Champions\Champion;

interface ChampionRepositoryInterface
{
    public function save(Champion $champion);
    public function getAll(array $params): ResponseInterface;
    public function fetchById(int $championId, array $params): ResponseInterface;
}
