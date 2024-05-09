<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Intervention;
use App\Form\UploadPhotoType;
use App\Form\UploadDeviType;
use App\Repository\PhotoRepository;
use App\Repository\ClientRepository;
use App\Repository\DeviRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class InterventionController extends AbstractController
{
    #[Route('/intervention/{show}/{idInter}', name: 'app_intervention')]
    public function index(
        Request $request ,
        EntityManagerInterface $entityManager,
        string $idInter, string $show,
        ClientRepository $clients,
        DeviRepository $deviRepository,
        InterventionRepository $interventionRepository,
        PhotoRepository $photoRepository): Response
    {   
        if(!$this->getUser()) 
        {
            return $this->redirectToRoute('app_login');
        }
        
        $intervention = $interventionRepository->findOneBy(['id'=> $idInter]);
        $photoInter = $photoRepository->findBy(['idInter'=> $idInter]);
        $deviInter = $deviRepository->findBy(['idInter'=> $idInter]);
        $idClient = $intervention->getIdClient();
        $client = $clients->findOneBy(['id'=> $idClient]);

        $uploadPhotoForm = $this->createForm(UploadPhotoType::class);
        $uploadDeviForm = $this->createForm(UploadDeviType::class);

        $uploadPhotoForm->handleRequest($request);
        $uploadDeviForm->handleRequest($request);
       
        if($request->isMethod('POST') && $uploadPhotoForm->isSubmitted() && $uploadPhotoForm->isValid() )
        {
                    $photo = $uploadPhotoForm->getData();
                    $photo->setIdInter($intervention);
                    $entityManager->persist($photo);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_intervention', ['show' => 'photos', 'idInter' => $idInter]);   

        }

        if($request->isMethod('POST') && $uploadDeviForm->isSubmitted() && $uploadDeviForm->isValid() )
        {
                    $devi = $uploadDeviForm->getData();
                    $devi->setIdInter($intervention);
                    $entityManager->persist($devi);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_intervention', ['show' => 'photos', 'idInter' => $idInter]);   

        }

        return $this->render('intervention/index.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Fiche d\'intervention',
            'titreSideBar' => 'Informations client',
            'show' => $show,
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'client' => $client,
            'intervention' => $intervention,
            'photoInter' => $photoInter,
            'deviInter' => $deviInter,
            'uploadPhotoForm' => $uploadPhotoForm->createView(),
            'uploadDeviForm' => $uploadDeviForm->createView(),
            'visibility' => 'd-block']);
    }  
   
    // Ajout d'une nouvelle intervention
    #[Route('/nouvelleIntervention/{idClient}', name: 'app_nouvIntervention')]
    public function nouvelleIntervention( int $idClient,  ClientRepository $clientRepository ): Response
    {   
        // Si l'utilisateut n'est pas connecté retour à la page login
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        } 

        //Les variables nécessaires pour l'affichage de la page
        $vue = "partials/form/interForm.html.twig";
        $titrePage = "Création d'une nouvelle intervention";

        // Récupere le client grace à son id
        $client = $clientRepository->findOneBy(['id'=> $idClient]);
        
        // Création du formulaire de contact
        if( isset($_GET['dateFin']))
        {
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
                'statut' => $statut,
                'visibility' => 'd-block']);
        } 
        else 
        {
        //appel de la page provisoire
        return $this->render('/intervention/nouvelleInter.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => $titrePage,
            'vue' => $vue,
            'titreSideBar' => 'Informations client',
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'client' => $client,
            'visibility' => 'd-block']);
        }
    }

    #[Route('confirmeInter/{idClient}/{dateDebut}/{dateFin}/{description}/{note}/{statut}', name: 'app_confirmeInter')]
    public function ajoutIntervention(string $dateDebut, string $dateFin, string $description, string $statut, string $note, int $idClient, ClientRepository $clientRepository, EntityManagerInterface $entityManager): Response
    {   
        if(!$this->getUser()) 
        {
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