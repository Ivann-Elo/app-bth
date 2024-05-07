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

        // Récupere le client grace à son id
        $client = $clientRepository->findOneBy(['id'=> $idClient]);
        
        // Création du formulaire de contact 
        $intervention = new Intervention();
        $ajoutTacheForm = $this->createForm(InterventionFormType::class, $intervention);
        $ajoutTacheForm->handleRequest($request); 
        
        // Si le formulaire est soumis et valide
        if($ajoutTacheForm->isSubmitted() && $ajoutTacheForm->isValid()) {

            // date de création de l'intervention
            $dateCreation = new DateTimeImmutable();

            // Récupére les données du formulaire
            $formData = $ajoutTacheForm->getData();

            // Prépare les données pour les enregistrer dans la base de données
            $intervention->setIdClient($client);
            $intervention->setRueInter($client->getRueClient());
            $intervention->setZipInter($client->getZipClient());
            $intervention->setVilleInter($client->getVilleClient());
            $intervention->setDescription($formData->getDescription());
            $intervention->setNote($formData->getNote());
            $intervention->setDateDebut($formData->getDateDebut());
            $intervention->setDateFin($formData->getDateFin());
            $intervention->setStatut($formData->getStatut());
            $intervention->setDateCreation($dateCreation);

            //Confirmer l'ajout de l'intervention
            $formConfirmation = $this->createFormBuilder($formData)
            ->add('confirmer', SubmitType::class, ['label' => 'Confirmer'])
            ->getForm();

            $formConfirmation->handleRequest($request);
            var_dump($request);
            die();
            
            if($formConfirmation->isSubmitted() && $formConfirmation->isValid()) {
                $entityManager->persist($intervention);
                $entityManager->flush();
                return $this->redirectToRoute('app_intervention', [
                    'show' => 'photos',
                    'idInter' => $intervention->getId()]);
            }
            // Enregistre les données dans la base de données
            // $entityManager->persist($intervention);
            // $entityManager->flush();

            // return $this->redirectToRoute('app_intervention', [
            //     'show' => 'photos',
            //     'idInter' => $intervention->getId()]);
        } 

        //appel de la page provisoire
        return $this->render('/intervention/nouvelleInter.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Création d\'une nouvelle intervention',
            'titreSideBar' => 'Informations client',
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'client' => $client,
            'formInter' => $ajoutTacheForm->createView()
             ]);
        }
    
    
}
