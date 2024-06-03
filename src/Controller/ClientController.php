<?php

namespace App\Controller;

use App\Form\ModifClientType;
use App\Form\AjoutClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Request;
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
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'Clients' => $client,
            'visibility' => 'd-block'
        ]);
    }

    #[Route('/ajoutClient' , name:'ajout_client')]
    public function ajoutClient(EntityManagerInterface $entityManager ,ClientRepository $clientRepository, Request $request): Response
    {    
        // Création du formulaire
        $formAjoutClient = $this->createForm(AjoutClientType::class);
        $client = "";

        //Traitement de la requetes
        $formAjoutClient->handleRequest($request); 

        if ($formAjoutClient->isSubmitted() && $formAjoutClient->isValid()) {
            $client = $formAjoutClient->getData();
            $entityManager->persist($client);
            try {
                $entityManager->flush();
                $this->addFlash('successClient', 'Client ajouté avec succès');

            } catch (\Exception $e) {
                $this->addFlash('erreurAjout', 'Cette adresse Mail est déjà utilisée');
            }
        }

        $formAjoutClient = $formAjoutClient->createView();

       return $this->render('client/ajoutClient.html.twig', [
            'titrePage' => 'Ajout d\'un client',
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'visibility' => 'd-block',
            'formAjoutClient' => $formAjoutClient,
            'client' => $client
       ]);
    }

    #[Route('/modifierClient{id}', name: 'modifier_client')]
    public function modifierClient(InterventionRepository $InterventionRepository, int $id, EntityManagerInterface $entityManager, ClientRepository $clientRepository, Request $request): Response
    {
        $client = $clientRepository->findOneBy(['id' => $id]);
        $interventions = $InterventionRepository->findBy(['idClient'=> $client->getId()]);
        $interventionsTerminees = $InterventionRepository->findBy(['idClient'=> $client->getId(), 'statut' => 'Terminée']);
        $interventionsEnCours = $InterventionRepository->findBy(['idClient'=> $client->getId(), 'statut' => 'En cours']);

        $formModifierClient = $this->createForm(ModifClientType::class, $client);
        $formModifierClient->handleRequest($request);

        if ($formModifierClient->isSubmitted() && $formModifierClient->isValid()) {
            $client = $formModifierClient->getData();
            $entityManager->persist($client);
            try {
                $entityManager->flush();
                $this->addFlash('success', 'Client modifié avec succès');
                sleep(1);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur lors de la modification du client');
                $this->addFlash('danger', $e->getMessage());
                sleep(1);
            }

            return $this->redirectToRoute('app_client', ['id' => $client->getId()]);
        }

        $formModifierClient = $formModifierClient->createView();

        return $this->render('client/modifClient.html.twig', [
            'titrePage' => 'Modification d\'un client',
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'visibility' => 'd-block',
            'formModifierClient' => $formModifierClient,
            'client' => $client,
            'interventions' => $interventions,
            'interventionsTerminees' => $interventionsTerminees,
            'interventionsEnCours' => $interventionsEnCours
        ]);
    }

    #[Route('/supprimerClient{id}', name: 'supprimer_client')]
    public function supprimerClient(int $id, EntityManagerInterface $entityManager, ClientRepository $clientRepository): Response
    {
        $client = $clientRepository->findOneBy(['id' => $id]);
        $entityManager->remove($client);
        try {
            $entityManager->flush();
            $this->addFlash('success', 'Client supprimé avec succès');
            sleep(1);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur lors de la suppression du client');
            $this->addFlash('danger', $e->getMessage());
            sleep(1);
        }

        return $this->redirectToRoute('liste_clients');
    }
}
