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

        if ($this->getUser()) {
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'titrePage' => 'Tableau de bord',
                'titreSideBar' => 'Nouveau client',
                'email' => $this->getUser()->getEmail(),
                'date' => (new \DateTime())->format('d-m-Y')
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }    
}
