<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class InterventionController extends AbstractController
{
    #[Route('/intervention/{show}', name: 'app_intervention')]
    public function index(Request $request): Response
    {   
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $show = $request->attributes->get('show');

        return $this->render('intervention/index.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Fiche d\'intervention',
            'titreSideBar' => 'Informations client',
            'show' => $show,
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y')
        ]);
    }  

    #[Route('/nouvelleIntervention{id}', name: 'app_nouvIntervention')]
    public function nouvelleIntervention(ClientRepository $clientRepository, Request $request): Response
    {   
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        $id = $request->attributes->get('id'); 
        $client = $clientRepository->findOneBy(['id'=> $id]);
        return $this->render('intervention/nouvelleInter.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Nouvelle intervention',
            'titreSideBar' => 'Informations client',
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'client' => $client
        ]);
    }


}
