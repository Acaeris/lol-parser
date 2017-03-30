<?php

namespace LeagueOfData\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * Index Action
     * @Route("/", name="homepage")
     * @param Request $request
     * @return type
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', []);
    }
}
