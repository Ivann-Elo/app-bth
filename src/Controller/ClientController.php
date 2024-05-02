<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    #[Route('/client{id}', name: 'app_client')]
    public function index(ClientRepository $clientRepository, Request $request): Response
    {      
        $id = $request->attributes->get('id'); 
        $client = $clientRepository->findOneBy(['id'=> $id ]);

        return $this->render('client/index.html.twig', [
            'titrePage' => 'Détails du client',
            'titreSideBar' => 'Informations client',
            'email' => $this->getUser()->getEmail(),
            'date' => (new \DateTime())->format('d-m-Y'),
            'client' => $client
        ]);
    }
}
