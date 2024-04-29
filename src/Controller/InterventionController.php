<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class InterventionController extends AbstractController
{
    #[Route('/intervention/{show}', name: 'app_intervention')]
    public function index(Request $request): Response
    {    
        $show = $request->attributes->get('show');

        return $this->render('intervention/index.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Fiche d\'intervention',
            'titreSideBar' => 'Informations client',
            'show' => $show
        ]);
    }  

    #[Route('/nouvelleIntervention', name: 'app_nouvIntervention')]
    public function nouvelleIntervention(): Response
    {
        return $this->render('intervention/nouvelleInter.html.twig', [
            'controller_name' => 'InterventionController',
            'titrePage' => 'Nouvelle intervention',
            'titreSideBar' => 'Informations client'
        ]);
    }


}
