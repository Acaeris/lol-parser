<?php

namespace LeagueOfData\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * Index Action
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $db = $this->get('champion-db');
        //$serializer = $this->get('serializer');

        $annie = $db->fetch('7.9.1', 1);

        return new JsonResponse($annie[1]->getName());
    }

    /**
     * Champion Action
     * @Route("/champion/{id}", name="champion")
     */
    public function championAction(Request $request, $id)
    {
        $db = $this->get('champion-db');
        $serializer = $this->get('serializer');

        $annie = $db->fetch('7.9.1', $id);

        return new Response(
            $serializer->serialize($annie, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}
