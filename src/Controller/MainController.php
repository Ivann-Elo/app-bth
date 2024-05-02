<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(ClientRepository $ClientRepository, InterventionRepository $interventionRepository): Response
    {   
        if ($this->getUser()) { 
                $clients = $ClientRepository->findAll();
                #$client = $ClientRepository->findOneBy(['id' => 12]); // test / debug
                #dd($clients[1]->getVilleClient());

                $Interventions = $ClientRepository->findAll();
                return $this->render('main/index.html.twig', [
                    'controller_name' => 'MainController',
                    'titrePage' => 'Tableau de bord',
                    'titreSideBar' => 'Nouveau client',
                    'email' => $this->getUser()->getEmail(),
                    'date' => (new \DateTime())->format('d-m-Y'),
                    'Clients' => $clients,
                    'Interventions' => $Interventions,
                ]);

            
        } else {
            return $this->redirectToRoute('app_login');
        }
    }    
}
