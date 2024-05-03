<?php

namespace App\Controller;

use App\Entity\Intervention;
use App\Form\InterventionFormType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterventionRepository;
use Doctrine\DBAL\Types\DateImmutableType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InterventionController extends AbstractController
{
    #[Route('/intervention/{show}', name: 'app_intervention')]
    public function index(string $show , ClientRepository $Clients): Response
    {   
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        $client = $Clients->findAll();
        return $this->render('intervention/index.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Fiche d\'intervention',
            'titreSideBar' => 'Informations client',
            'show' => $show,
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'Client' => $client
        ]);
    }  

    #[Route('/nouvelleIntervention{id}', name: 'app_nouvIntervention')]
    public function nouvelleIntervention(int $id, Request $request,  ClientRepository $clientRepository): Response
    {   
        // Si l'utilisateut n'est pas connecté retour à la page login
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupere le client grace à son id
        $client = $clientRepository->findOneBy(['id'=> $id]);

        // Création du formulaire de contact 
        $intervention = new Intervention();
        $ajoutTacheForm = $this->createForm(InterventionFormType::class, $intervention);
        $ajoutTacheForm->handleRequest($request);

        //appel de la page provisoire
        return $this->render('/intervention/nouvelleInter.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Nouvelle intervention',
            'titreSideBar' => 'Informations client',
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'client' => $client,
            'formInter' => $ajoutTacheForm->createView()
             ]);
        }
    
}
