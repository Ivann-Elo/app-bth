<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Intervention;
use Doctrine\ORM\EntityManager;
use App\Form\InterventionFormType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterventionRepository;
use Doctrine\DBAL\Types\DateImmutableType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InterventionController extends AbstractController
{
    #[Route('/intervention/{show}/{idInter}', name: 'app_intervention')]
    public function index(string $idInter, string $show , ClientRepository $clients, InterventionRepository $interventionRepository): Response
    {   

        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        $intervention = $interventionRepository->findOneBy(['id'=> $idInter]);
        $idClient = $intervention->getIdClient();
        $client = $clients->findOneBy(['id'=> $idClient]);

        return $this->render('intervention/index.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Fiche d\'intervention',
            'titreSideBar' => 'Informations client',
            'show' => $show,
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'client' => $client,
            'intervention' => $intervention
        ]);
    }  

    #[Route('/nouvelleIntervention/{idClient}', name: 'app_nouvIntervention')]
    public function nouvelleIntervention( int $idClient, Request $request,  ClientRepository $clientRepository , EntityManagerInterface $entityManager): Response
    {   
        // Si l'utilisateut n'est pas connecté retour à la page login
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        } 

        //Les vairables necessaires pour l'affichage de la page
        $vue = "partials/form/interForm.html.twig";
        $titrePage = "Création d'une nouvelle intervention";

        // Récupere le client grace à son id
        $client = $clientRepository->findOneBy(['id'=> $idClient]);
        
        // Création du formulaire de contact
        if( isset($_GET['dateFin'])){
            $vue = "partials/confirmeInter.html.twig";
            $titrePage = "Confirmation de l'intervention"; 
            $choixAdresse = $_GET['choixAdresse'];
            $dateDebut = $_GET['dateDebut'];
            $dateFin = $_GET['dateFin'];
            $description = $_GET['description'];
            $note = $_GET['note'];
            $statut = $_GET['statut'];

            return $this->render('/intervention/nouvelleInter.html.twig', [
                'controller_name' => 'InterventionController',
                'titrePage' => $titrePage,
                'vue' => $vue,
                'titreSideBar' => 'Informations client',
                'email' => $this->getUser()->getEmail(),
                'date' => (new \DateTime())->format('d-m-Y'),
                'client' => $client,
                'choixAdresse' => $choixAdresse,
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
                'description' => $description,
                'note' => $note,
                'statut' => $statut

                 ]);
            } 
            else {
            //appel de la page provisoire
            return $this->render('/intervention/nouvelleInter.html.twig', [
                'controller_name' => 'InterventionController',
                'titrePage' => $titrePage,
                'vue' => $vue,
                'titreSideBar' => 'Informations client',
                'email' => $this->getUser()->getEmail(),
                'date' => (new \DateTime())->format('d-m-Y'),
                'client' => $client,
                
                ]);
            }
        
    }

    #[Route('confirmeInter/{idClient}/{dateDebut}/{dateFin}/{description}/{note}/{statut}', name: 'app_confirmeInter')]
    public function ajoutIntervention(string $dateDebut, string $dateFin, string $description, string $statut, string $note, int $idClient, ClientRepository $clientRepository, EntityManagerInterface $entityManager): Response{
        
         if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        } 
        $client = $clientRepository->findOneBy(['id'=> $idClient]);

        $intervention = new Intervention();
        $intervention->setIdClient($client);
        $intervention->setDateCreation(new DateTimeImmutable());
        $intervention->setDateDebut(new DateTimeImmutable($dateDebut));
        $intervention->setDateFin(new DateTimeImmutable($dateFin));
        $intervention->setStatut($statut);
        $intervention->setDescription($description);
        $intervention->setNote($note);
        $intervention->setRueInter($client->getRueClient());
        $intervention->setVilleInter($client->getVilleClient());
        $intervention->setZipInter($client->getZipClient());


        $entityManager->persist($intervention);
        $entityManager->flush();

        return $this->redirectToRoute('app_intervention', [
            'show' => 'photos',
            'idInter' => $intervention->getId()]);
    } 
             
}
