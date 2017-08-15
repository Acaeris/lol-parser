<?php
namespace LeagueOfData\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use LeagueOfData\Repository\Summoner\SqlSummonerRepository;

class SummonerController extends Controller
{
    public function byIdAction(Request $request, SqlSummonerRepository $repository) : Response
    {
        $summonerId = $request->query->get('id');
        $region = $request->query->get('region') ?? 'euw';
        $summoner = $repository->fetch(
            'SELECT * FROM summoners WHERE summoner_id = :summoner_id AND region = :region',
            [ 'summoner_id' => $summonerId, 'region' => $region ]
        );

        return new Response(
            $this->renderView('api/summonerDetails.html.twig', ['summoner' => $summoner[$summonerId]]),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json',
                'Access-Control-Allow-Origin' => '*'
            ]
        );
    }

    public function byNameAction(Request $request, SqlSummonerRepository $repository) : Response
    {
        $summonerName = $request->query->get('name');
        $region = $request->query->get('region') ?? 'euw';
        $summoner = $repository->fetch("SELECT * FROM summoners WHERE summoner_name = :summoner_name "
            . "AND region = :region", ['summoner_name' => $summonerName, 'region' => $region]);
        $summonerIDs = array_keys($summoner);

        return new Response(
            $this->renderView('api/summonerDetails.html.twig', ['summoner' => $summoner[$summonerIDs[0]]]),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json',
                'Access-Control-Allow-Origin' => '*'
            ]
        );
    }

    public function listAction(Request $request, SqlSummonerRepository $repository) : Response
    {
        $select = "SELECT * FROM summoners WHERE region = :region";
        $params = [ 'region' => $request->query->get('region') ?? 'euw'];
        if (null !== $request->query->get('s') && '' !== $request->query->get('s')) {
            $select .= " AND summoner_name LIKE :summoner_name";
            $params['summoner_name'] = '%'.$request->query->get('s').'%';
        }
        $select .= " ORDER BY summoner_name ASC";
        $summoners = $repository->fetch($select, $params);

        return new Response(
            $this->renderView('api/summonerList.html.twig', ['summoners' => $summoners]),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json',
                'Access-Control-Allow-Origin' => '*'
            ]
        );
    }
}
