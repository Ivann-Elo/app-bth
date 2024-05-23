<?php

namespace App\Controller;

use App\Form\AjoutTacheType;
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

    public function index(EntityManagerInterface $entityManager ,Request $request, AjoutTacheType $ClientForm, ClientRepository $ClientRepository, InterventionRepository $InterventionRepository, CategorieRepository $categorieTacheRepository): Response
    {   
        // Si l'utilisateur est connecté
        if ($this->getUser()) { 
        
                // Récupération des clients et des interventions
                $clients = $ClientRepository->findAll();
                $interventionsEncours = $InterventionRepository->findby(['statut' => 'En cours']);
                $categorieTaches = $categorieTacheRepository->findAll();

                // Création du formulaire d'ajout de client
                $formAjoutClient = $this->createForm(AjoutClientType::class);

                // Traitement du formulaire d'ajout de client
                $formAjoutClient->handleRequest($request);

                if ($formAjoutClient->isSubmitted() && $formAjoutClient->isValid()) {
                    $client = $formAjoutClient->getData();
                    $entityManager->persist($client);
                    $entityManager->flush();
                    return $this->redirectToRoute('main');
                }

                // Affichage de la page d'accueil
                return $this->render('main/index.html.twig', [
                    'controller_name' => 'MainController',
                    'titrePage' => 'Tableau de bord',
                    'titreSideBar' => 'Nouveau client',
                    'email' => $this->getUser()->getEmail(),
                    'date' => (new \DateTime())->format('l j F Y'),
                    'Clients' => $clients,
                    'categorieTaches' => $categorieTaches,
                    'interventions' => $interventionsEncours,
                    'visibility' => 'd-block',
                    'formAjoutClient' => $formAjoutClient->createView()
                ]);
        
        // Sinon redirection vers la page d'accueil
        } else {
            return $this->redirectToRoute('app_login');
        }
    }   

    #[Route('/interList/{list}', name: 'interList')]
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
                    'date' => (new \DateTime())->format('l j F Y'),
                    'Clients' => $clients,
                    'interventions' => $interList,
                    'visibility' => 'd-block'
                ]);
        
        // Sinon redirection vers la page d'accueil
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}

