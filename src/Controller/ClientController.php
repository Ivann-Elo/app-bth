<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    #[Route('/client{id}', name: 'app_client')]
    public function index(int $id, ClientRepository $clientRepository, InterventionRepository $InterventionRepository): Response
    {      
        $client = $clientRepository->findOneBy(['id'=> $id ]);
        $interventions = $InterventionRepository->findBy(['idClient'=> $client->getId()]);
        $interventionsTerminees = $InterventionRepository->findBy(['idClient'=> $client->getId(), 'statut' => 'Terminée']);
        $interventionsEnCours = $InterventionRepository->findBy(['idClient'=> $client->getId(), 'statut' => 'En cours']);

        return $this->render('client/index.html.twig', [
            'titrePage' => 'Détails du client',
            'titreSideBar' => 'Informations client',
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'client' => $client,
            'interventions' => $interventions,
            'interventionsTerminees' => $interventionsTerminees,
            'interventionsEnCours' => $interventionsEnCours,
            'visibility' => 'd-block'
        ]);
    } 

    #[Route('/listeClients' , name: 'liste_clients')]
    public function searchClient(ClientRepository $clientRepository): Response 
    {
        $client = $clientRepository->findAll(); 
        return $this->render('client/searchClient.html.twig', [
            'titrePage' => 'Mes clients',
            'titreSideBar' => 'Informations client',
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'Clients' => $client,
            'visibility' => 'd-block'
        ]);
    }
}
