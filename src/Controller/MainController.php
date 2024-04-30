<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'titrePage' => 'Tableau de bord',
            'titreSideBar' => 'Nouveau client'
        ]);
    }

    #[Route('/calendrier', name: 'calendrier')]
    public function calendrier(): Response
    {
        return $this->render('google/index.html.twig', [
            'controller_name' => 'MainController',
            'titrePage' => 'Calendrier',
            'titreSideBar' => 'Calendrier'
        ]);
    }
}
