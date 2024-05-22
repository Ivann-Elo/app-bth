<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\AjoutTacheType;
use App\Form\ModifInterType;
use App\Form\UploadDeviType;
use App\Form\UploadPhotoType;
use App\Form\InterventionType;
use App\Form\UploadFactureType;
use App\Form\AjoutCategorieType;
use App\Repository\DeviRepository;
use App\Repository\PhotoRepository;
use App\Repository\TacheRepository;
use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use App\Repository\CategorieRepository;
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
        CategorieRepository $categorieRepository,
        ClientRepository $clients,
        DeviRepository $deviRepository,
        EntityManagerInterface $entityManager,
        FactureRepository $factureRepository,
        InterventionRepository $interventionRepository,
        PhotoRepository $photoRepository,
        Request $request ,
        string $idInter, string $show,
        TacheRepository $tacheRepository
        ): Response
    {   
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        //Déclaration des variables
        $tabFormTache = [];
        
        //Récupération des données
        $intervention = $interventionRepository->findOneBy(['id'=> $idInter]);
        $photoInter = $photoRepository->findBy(['idInter'=> $idInter]);
        $deviInter = $deviRepository->findBy(['idInter'=> $idInter]);
        $factureInter = $factureRepository->findBy(['idInter'=> $idInter]);
        $categorieTache = $categorieRepository->findBy(['idInter'=> $idInter]);
        $idClient = $intervention->getIdClient();
        $client = $clients->findOneBy(['id'=> $idClient]);
        $tache = $tacheRepository->findAll();

        //Création des formulaires
        $uploadPhotoForm = $this->createForm(UploadPhotoType::class);
        $uploadDeviForm = $this->createForm(UploadDeviType::class);
        $uploadFactureForm = $this->createForm(UploadFactureType::class);
        $ajoutCategorieForm = $this->createForm(AjoutCategorieType::class);
        
        //Traitement des formulaires
        $uploadPhotoForm->handleRequest($request);
        $uploadDeviForm->handleRequest($request);
        $uploadFactureForm->handleRequest($request);
        $ajoutCategorieForm->handleRequest($request);
        
        // Gérer les requêtes de chaque formulaire
        foreach ($categorieTache as $categorie) {
            $form = $this->createForm(AjoutTacheType::class, null, [
                'categorie' => $categorie,
            ]);
            $form->handleRequest($request);

            // Si le formulaire est soumis et valide, traiter les données
            if ($form->isSubmitted() && $form->isValid()) {
                $entity = $form->getData();
                $categorieId = $form->get('idCat')->getData();
                $categorieEntity = $entityManager->getRepository(Categorie::class)->find($categorieId);
                $entity->setIdCat($categorieEntity);
                $entity->setStatutTache('En cours');
                $entityManager->persist($entity);
                $entityManager->flush();

                return $this->redirectToRoute('app_intervention', [
                    'show' => 'taches',
                    'idInter' => $idInter,
                ]);
            }

            // Ajouter la vue du formulaire au tableau après traitement de la requête
            $tabFormTache[$categorie->getNomCat()] = $form->createView();
        }

        //Traitement du formulaire d'ajout de catégorie
        if($ajoutCategorieForm->isSubmitted() && $ajoutCategorieForm->isValid())
        {
            $entity = $ajoutCategorieForm->getData();
            $entity->setIdInter($intervention);
            $entity->setCreatedBy($this->getUser());
            $entityManager->persist($entity);
            $entityManager->flush();
            return $this->redirectToRoute('app_intervention', [
                'show' => 'taches', 
                'idInter' => $idInter,
            ]);
        }
        
        //Déclaration des variables pour la persistance des données
        $persistPhoto = $this->persist($idInter, $request, $intervention, $entityManager, $uploadPhotoForm);
        $persitDevi = $this->persist($idInter, $request, $intervention, $entityManager, $uploadDeviForm);
        $persistFacture = $this->persist($idInter, $request, $intervention, $entityManager, $uploadFactureForm);

        //Persistance des données
        $persistPhoto; $persitDevi; $persistFacture;

        return $this->render('intervention/index.html.twig', [
            'ajoutCategorieForm' => $ajoutCategorieForm->createView(),

            'categorieTaches' => $categorieTache,
            'client' => $client,
            'controller_name' => 'InterventionController',
            'date' => (new \DateTime())->format('l j F Y'),
            'deviInter' => $deviInter,
            'email' => $this->getUser()->getEmail(),
            'factureInter' => $factureInter,
            'intervention' => $intervention,
            'photoInter' => $photoInter,
            'show' => $show,
            'titrePage' => 'Fiche d\'intervention',
            'titreSideBar' => 'Informations client',
            'taches' => $tache,
            'tacheForms' => $tabFormTache,
            'uploadPhotoForm' => $uploadPhotoForm->createView(),
            'uploadDeviForm' => $uploadDeviForm->createView(),
            'uploadFactureForm' => $uploadFactureForm->createView(),
            'visibility' => 'd-block',
            ]);
    }
    
    // Ajout d'une nouvelle intervention
    #[Route('/nouvelleIntervention/{idClient}', name: 'app_nouvIntervention')]
    public function nouvelleIntervention( int $idClient,  ClientRepository $clientRepository , Request $request, EntityManagerInterface $entityManager): Response
    {   
        // Si l'utilisateut n'est pas connecté retour à la page login
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        } 

        // Récupere le client grace à son id
        $client = $clientRepository->findOneBy(['id'=> $idClient]);
        
        // Création du formulaire de contact
        $interventionForm = $this->createForm(InterventionType::class);
        $interventionForm->handleRequest($request);

        if($interventionForm->isSubmitted() && $interventionForm->isValid())
        {
            $entity = $interventionForm->getData();
            $entity->setIdClient($client);
            $entity->setRueinter($client->getRueClient());
            $entity->setVilleinter($client->getVilleClient());
            $entity->setZipInter($client->getZipClient());
            $entity->setDateCreation(new \DateTimeImmutable());
            $entityManager->persist($entity);
            $entityManager->flush();
            return $this->redirectToRoute('app_intervention', [
                'idInter' => $entity->getId(),
                'show' => 'taches',
            ]);
        }

        //appel de la page provisoire
        return $this->render('/intervention/nouvelleInter.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Création d\'une nouvelle intervention',
            'interventionForm' => $interventionForm->createView(),
            'titreSideBar' => 'Informations client',
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('l j F Y'),
            'client' => $client,
            'visibility' => 'd-block']);
    }

    // Modification d'une intervention
    #[Route('/intervention/modifier/{show}/{idInter}', name: 'modifier_inter')]
    public function modifierInter(
        
        int $idInter,
        EntityManagerInterface $entityManager,
        InterventionRepository $interventionRepository,
        ClientRepository $clients,
        DeviRepository $deviRepository,
        FactureRepository $factureRepository,
        PhotoRepository $photoRepository,
        Request $request
        ): Response
    {   
        if(!$this->getUser()) { return $this->redirectToRoute('app_login'); } 

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
            return $this->redirectToRoute('app_intervention', [
                'idInter' => $idInter,
                'show' => 'photos',
            ]);
        }

        return $this->render('intervention/modifInter.html.twig', [
            'controller_name' => 'InterventionController',
            'client' => $client,
            'date' => (new \DateTime())->format('l j F Y'),
            'deviInter' => $deviInter,
            'email' => $this->getUser()->getEmail(),
            'factureInter' => $factureInter,
            'intervention' => $intervention,
            'photoInter' => $photoInter,
            'modifInterForm' => $modifInterForm->createView(),
            'titrePage' => 'Modification d\'une intervention',
            'titreSideBar' => 'Informations client',
            'visibility' => 'd-block',
        ]);
    }

    // Supprimer une tache 
    #[Route('/supprimerTache/{idTache}/{idInter}', name: 'supprimer_tache')]
    public function supprimerTache(int $idInter,int $idTache, EntityManagerInterface $entityManager, TacheRepository $tacheRepository): Response
    {   
        $tache = $tacheRepository->findOneBy(['id'=> $idTache]);
        $entityManager->remove($tache);
        $entityManager->flush();
        return $this->redirectToRoute('app_intervention', [
            'idInter' => $idInter,
            'show' => 'taches',
        ]);
    }

    // Suppression d'une catégorie
    #[Route('/supprimerCategorie/{idCat}/{idInter}', name: 'supprimer_categorie')]
    public function supprimerCategorie(int $idInter, int $idCat, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository): Response
    {   
        $categorie = $categorieRepository->findOneBy(['id'=> $idCat]);
        $entityManager->remove($categorie);
        $entityManager->flush();
        return $this->redirectToRoute('app_intervention', [
            'idInter' => $idInter,
            'show' => 'taches',
        ]);
    }

    // Suppression d'une intervention
    #[Route('/supprimer/{idInter}', name: 'supprimer_inter')]    
    public function supprimerInter(int $idInter, EntityManagerInterface $EntityManager, InterventionRepository $intervention): Response {   
        $intervention = $intervention->find($idInter);
        $EntityManager->remove($intervention);
        $EntityManager->flush();
        return $this->redirectToRoute('main');
    }
    
    // Fonction de persistance des données  
    private function persist($idInter, $request, $intervention, $entityManager, $form){
        if($request->isMethod('POST') && $form->isSubmitted() && $form->isValid() )
        {
                    $entity = $form->getData();
                    $entity->setIdInter($intervention);
                    $entityManager->persist($entity);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_intervention', [
                        'idInter' => $idInter,
                        'show' => 'photos',
                    ]);   
        }
    }
}