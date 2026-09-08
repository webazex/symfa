<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]

    public function home():Response
    {
        return new Response("Sooo");
    }

    #[Route('/fuck/you/{name}', name: 'fuckyou')]

    public function fuckyou(string $name):Response
    {
        return $this->render('default/index.html.twig', [
            'name' => $name,
        ]);
    }
}
