<?php

namespace LeagueOfData\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * Index Action
     */
    public function indexAction(Request $request)
    {
        $championDB = $this->get('champion-db');
        $serializer = $this->get('serializer');

        $annie = $championDB->fetch('7.9.1', 1);

        return new Response(
            $serializer->serialize($annie[1], 'json'),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json',
                'Access-Control-Allow-Origin' => '*'
            ]
        );
    }
}
