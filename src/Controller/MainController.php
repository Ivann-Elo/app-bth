<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(ClientRepository $ClientRepository, InterventionRepository $InterventionRepository): Response
    {   
        // Si l'utilisateur est connecté
        if ($this->getUser()) { 

                // Récupération des clients et des interventions
                $clients = $ClientRepository->findAll();
                $interventions = $InterventionRepository->findAll();
                
                // Affichage de la page d'accueil
                return $this->render('main/index.html.twig', [
                    'controller_name' => 'MainController',
                    'titrePage' => 'Tableau de bord',
                    'titreSideBar' => 'Nouveau client',
                    'email' => $this->getUser()->getEmail(),
                    'date' => (new \DateTime())->format('d-m-Y'),
                    'Clients' => $clients,
                    'interventions' => $interventions,
                ]);
        
        // Sinon redirection vers la page d'accueil
        } else {
            return $this->redirectToRoute('app_login');
        }
    }    
}
