<?php

namespace LeagueOfData\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChampionController extends Controller
{
    public function byIdAction(Request $request, $id)
    {
        $db = $this->get('champion-db');
        $serializer = $this->get('serializer');
        $champion = $db->fetch('7.9.2', $id);

        return new Response(
            $serializer->serialize($champion[$id], 'json'),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json',
                'Access-Control-Allow-Origin' => '*'
            ]
        );
    }
}
