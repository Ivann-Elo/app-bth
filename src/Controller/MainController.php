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
                $interventionsEncours = $InterventionRepository->findby(['statut' => 'En cours']);

                // Affichage de la page d'accueil
                return $this->render('main/index.html.twig', [
                    'controller_name' => 'MainController',
                    'titrePage' => 'Tableau de bord',
                    'titreSideBar' => 'Nouveau client',
                    'email' => $this->getUser()->getEmail(),
                    'date' => (new \DateTime())->format('d-m-Y'),
                    'Clients' => $clients,
                    'interventions' => $interventionsEncours,
                ]);
        
        // Sinon redirection vers la page d'accueil
        } else {
            return $this->redirectToRoute('app_login',['list' => 'Encours']);
        }
    }   
    
    #[Route('/{{list}}', name: 'interList')]
    public function afficheSelectInter(string $list, ClientRepository $ClientRepository, InterventionRepository $InterventionRepository): Response
    {    
       
        // Si l'utilisateur est connecté
        if ($this->getUser()) { 
        
            // Récupération des clients et des interventions
                $clients = $ClientRepository->findAll();
                $interventions = $InterventionRepository->findAll();
                $interventionsEncours = $InterventionRepository->findby(['statut' => 'En cours']);
                $interventionsTerminee = $InterventionRepository->findby(['statut' => 'Terminee']);
                $interventionsAvenir = $InterventionRepository->findby(['statut' => 'A venir']);

                switch ($list) {
                    case 'Encours':
                        $interList = $interventionsEncours;
                        break;
                    case 'Terminee':
                        $interList = $interventionsTerminee;
                        break;
                    case 'Avenir':
                        $interList = $interventionsAvenir;
                        break;
                    default:
                        $interList = $interventions;
                        break;
                }

                // Affichage de la page d'accueil
                return $this->render('main/index.html.twig', [
                    'controller_name' => 'MainController',
                    'titrePage' => 'Tableau de bord',
                    'titreSideBar' => 'Nouveau client',
                    'email' => $this->getUser()->getEmail(),
                    'date' => (new \DateTime())->format('d-m-Y'),
                    'Clients' => $clients,
                    'interventions' => $interList,
                ]);
        
        // Sinon redirection vers la page d'accueil
        } else {
            return $this->redirectToRoute('app_login');
        }

    }
}
