<?php

namespace App\Controller;

use App\Form\AjoutClientType;
use App\Repository\ClientRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MainController extends AbstractController

{    
    #[Route('/', name: 'main')]
    public function index(Request $request, ClientRepository $ClientRepository, InterventionRepository $InterventionRepository, CategorieRepository $categorieTacheRepository): Response
    {   
        // Si l'utilisateur est connecté
        if ($this->getUser()) { 
        
                // Récupération des clients et des interventions
                $clients = $ClientRepository->findAll();
                $interventions = $InterventionRepository->findAll();
                $interventionsEnCours = $InterventionRepository->findby(['statut' => 'En cours']);
                $interventionsTerminee = $InterventionRepository->findby(['statut' => 'Archivée']);
                $categorieTaches = $categorieTacheRepository->findAll();

                // Affichage de la page d'accueil
                return $this->render('main/index.html.twig', [
                    'controller_name' => 'MainController',
                    'titrePage' => 'Tableau de bord',
                    'titreSideBar' => 'Nouveau client',
                    'email' => $this->getUser()->getEmail(),
                    'date' => (new \DateTime())->format('l j F Y'),
                    'Clients' => $clients,
                    'interventions' => $interventions,
                    'interventionsEnCours' => $interventionsEnCours,
                    'interventionsTerminee' => $interventionsTerminee,
                    'categorieTaches' => $categorieTaches,
                    'visibility' => 'd-block',
                    'location' => 'home',
                ]);
        
        // Sinon redirection vers la page d'accueil
        } else {
            return $this->redirectToRoute('app_login');
        }
    }   
}

