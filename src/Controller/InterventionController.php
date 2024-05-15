<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Intervention;
use App\Form\ModifInterType;
use App\Form\UploadDeviType;
use App\Form\UploadPhotoType;
use App\Form\UploadFactureType;
use App\Repository\DeviRepository;
use App\Repository\PhotoRepository;
use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InterventionController extends AbstractController
{
    #[Route('/intervention/{show}/{idInter}', name: 'app_intervention')]
    public function index(
        Request $request ,
        EntityManagerInterface $entityManager,
        string $idInter, string $show,
        ClientRepository $clients,
        DeviRepository $deviRepository,
        FactureRepository $factureRepository,
        InterventionRepository $interventionRepository,
        PhotoRepository $photoRepository
        ): Response
    {   
        if(!$this->getUser()) 
        {
            return $this->redirectToRoute('app_login');
        }
        
        //Récupération des données
        $intervention = $interventionRepository->findOneBy(['id'=> $idInter]);
        $photoInter = $photoRepository->findBy(['idInter'=> $idInter]);
        $deviInter = $deviRepository->findBy(['idInter'=> $idInter]);
        $factureInter = $factureRepository->findBy(['idInter'=> $idInter]);
        $idClient = $intervention->getIdClient();
        $client = $clients->findOneBy(['id'=> $idClient]);

        //Création des formulaires
        $uploadPhotoForm = $this->createForm(UploadPhotoType::class);
        $uploadDeviForm = $this->createForm(UploadDeviType::class);
        $uploadFactureForm = $this->createForm(UploadFactureType::class);

        //Traitement des formulaires
        $uploadPhotoForm->handleRequest($request);
        $uploadDeviForm->handleRequest($request);
        $uploadFactureForm->handleRequest($request);
        
        //Déclaration des variables pour la persistance des données
        $persistPhoto = $this->persist($idInter, $request, $intervention, $entityManager, $uploadPhotoForm);
        $persitDevi = $this->persist($idInter, $request, $intervention, $entityManager, $uploadDeviForm);
        $persistFacture = $this->persist($idInter, $request, $intervention, $entityManager, $uploadFactureForm);

        //Persistance des données
        $persistPhoto;
        $persitDevi;
        $persistFacture;

        return $this->render('intervention/index.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Fiche d\'intervention',
            'titreSideBar' => 'Informations client',
            'show' => $show,
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('l j F Y'),
            'intervention' => $intervention,
            'client' => $client,
            'photoInter' => $photoInter,
            'deviInter' => $deviInter,
            'factureInter' => $factureInter,
            //'getInterInfos' => $getInterInfos,
            'uploadPhotoForm' => $uploadPhotoForm->createView(),
            'uploadDeviForm' => $uploadDeviForm->createView(),
            'uploadFactureForm' => $uploadFactureForm->createView(),
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
                'date' => (new \DateTime())->format('l j F Y'),
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
            'date' => (new \DateTime())->format('l j F Y'),
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

    // Modification d'une intervention
    #[Route('/intervention/modifier/{show}/{idInter}', name: 'modifier_inter')]
    public function modifierInter(
        int $idInter,
        string $show,
        EntityManagerInterface $entityManager,
        InterventionRepository $interventionRepository,
        ClientRepository $clients,
        DeviRepository $deviRepository,
        FactureRepository $factureRepository,
        PhotoRepository $photoRepository,
        Request $request
        ): Response
    {   
        $intervention = $interventionRepository->findOneBy(['id'=> $idInter]);
        $photoInter = $photoRepository->findBy(['idInter'=> $idInter]);
        $deviInter = $deviRepository->findBy(['idInter'=> $idInter]);
        $factureInter = $factureRepository->findBy(['idInter'=> $idInter]);
        $idClient = $intervention->getIdClient();
        $client = $clients->findOneBy(['id'=> $idClient]);

        $modifInterForm = $this->createForm(ModifInterType::class, $intervention);
        $modifInterForm->handleRequest($request);

        if($modifInterForm->isSubmitted() && $modifInterForm->isValid())
        {
            $entityManager->persist($intervention);
            $entityManager->flush();
            return $this->redirectToRoute('app_intervention', ['show' => 'photos', 'idInter' => $idInter]);
        }


        return $this->render('intervention/modifInter.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Modification d\'une intervention',
            'titreSideBar' => 'Informations client',
            'show' => $show,
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('l j F Y'),
            'client' => $client,
            'intervention' => $intervention,
            'photoInter' => $photoInter,
            'deviInter' => $deviInter,
            'factureInter' => $factureInter,
            'modifInterForm' => $modifInterForm->createView(),
            'visibility' => 'd-block']);
    }


    // Suppression d'une intervention
    #[Route('/supprimer/{idInter}', name: 'supprimer_inter')]    
    public function supprimerInter(int $idInter, EntityManagerInterface $EntityManager, InterventionRepository $intervention): Response
    {   
        $intervention = $intervention->find($idInter);
        $EntityManager->remove($intervention);
        $EntityManager->flush();
        return $this->redirectToRoute('main');
    }

    private function persist($idInter, $request, $intervention, $entityManager, $form){

        if($request->isMethod('POST') && $form->isSubmitted() && $form->isValid() )
        {
                    $entity = $form->getData();
                    $entity->setIdInter($intervention);
                    $entityManager->persist($entity);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_intervention', ['show' => 'photos', 'idInter' => $idInter]);   

        }
    }
}