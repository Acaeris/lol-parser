<?php

namespace LeagueOfData\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use LeagueOfData\Adapters\Request\ChampionRequest;

class ChampionController extends Controller
{
    public function byIdAction(Request $request)
    {
        $championId = $request->query->get('id');
        $version = (null !== $request->query->get('v')) ? $request->query->get('v') : '7.9.2';
        $database = $this->get('champion-db');
        $champion = $database->fetch(new ChampionRequest([
            'champion_id' => $championId,
            'version' => $version
        ], '*'));

        return new Response(
            $this->renderView('api/championAnalysis.html.twig', [ 'champion' => $champion[$championId] ]),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json',
                'Access-Control-Allow-Origin' => '*'
            ]
        );
    }
}
