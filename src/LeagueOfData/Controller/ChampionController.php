<?php
namespace LeagueOfData\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use LeagueOfData\Adapters\Request\ChampionRequest;

class ChampionController extends Controller
{

    public function byIdAction(Request $request) : Response
    {
        $championId = $request->query->get('id');
        $version = (null !== $request->query->get('v')) ? $request->query->get('v') : '7.9.1';
        $database = $this->get('champion-db');
        $champion = $database->fetch(new ChampionRequest([
            'champion_id' => $championId,
            'version' => $version
            ], 'SELECT * FROM champions WHERE champion_id = :champion_id AND version = :version'));

        return new Response(
            $this->renderView('api/championAnalysis.html.twig', ['champion' => $champion[$championId]]),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json',
                'Access-Control-Allow-Origin' => '*'
            ]
        );
    }

    public function listAction(Request $request) : Response
    {
        $select = "SELECT * FROM champions WHERE version = :version";
        $params = [ 'version' => (null !== $request->query->get('v')) ? $request->query->get('v') : '7.9.1' ];
        if (null !== $request->query->get('s') && '' !== $request->query->get('s')) {
            $select .= " AND champion_name LIKE :champion_name";
            $params['champion_name'] = '%'.$request->query->get('s').'%';
        }
        $select .= " ORDER BY champion_name ASC";
        $database = $this->get('champion-db');
        $champions = $database->fetch(new ChampionRequest($params, $select));

        return new Response(
            $this->renderView('api/championList.html.twig', ['champions' => $champions]),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json',
                'Access-Control-Allow-Origin' => '*'
            ]
        );
    }
}
