<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(ClientRepository $ClientRepository): Response
    {   
        if ($this->getUser()) { 
                $Client = $ClientRepository->findAll();
                dd($Client[1]);
                return $this->render('main/index.html.twig', [
                    'controller_name' => 'MainController',
                    'titrePage' => 'Tableau de bord',
                    'titreSideBar' => 'Nouveau client',
                    'email' => $this->getUser()->getEmail(),
                    'date' => (new \DateTime())->format('d-m-Y'),
                    'Client' => $Client,
                ]);

            
        } else {
            return $this->redirectToRoute('app_login');
        }
    }    
}
