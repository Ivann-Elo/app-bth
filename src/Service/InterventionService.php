<?php

namespace App\Service;

use DateTimeImmutable;
use App\Entity\Intervention;
use App\Entity\Tache;
use App\Entity\Categorie;
use App\Repository\ClientRepository;
use App\Repository\InterventionRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;

class InterventionService
{
    private $entityManager;
    private $interventionRepository;
    private $clientRepository;
    private $categorieRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        InterventionRepository $interventionRepository,
        ClientRepository $clientRepository,
        CategorieRepository $categorieRepository
    ) {
        $this->entityManager = $entityManager;
        $this->interventionRepository = $interventionRepository;
        $this->clientRepository = $clientRepository;
        $this->categorieRepository = $categorieRepository;
    }

    public function createIntervention(Intervention $intervention):void
    {
        $client = $this->clientRepository->find($intervention->getIdClient());
        if (!$client) {
            throw new \Exception('Client not found');
        }

        // $intervention = new Intervention();
        // $intervention->setIdClient($client);
        // $intervention->setDateCreation(new DateTimeImmutable());
        // $intervention->setDateDebut(new DateTimeImmutable($data->getDateDebut()));
        // $intervention->setDateFin(new DateTimeImmutable($data['dateFin']));
        // $intervention->setStatut($data['statut']);
        // $intervention->setDescription($data['description']);
        // $intervention->setNote($data['note']);
        // $intervention->setRueInter($client->getRueClient());
        // $intervention->setVilleInter($client->getVilleClient());
        // $intervention->setZipInter($client->getZipClient());

        $this->entityManager->persist($intervention);
        $this->entityManager->flush();
;
    }

    public function updateIntervention(Intervention $intervention): void
    {
        $this->entityManager->persist($intervention);
        $this->entityManager->flush();
    }

    public function deleteIntervention(int $idInter): void
    {
        $intervention = $this->interventionRepository->find($idInter);
        if (!$intervention) {
            throw new \Exception('Intervention not found');
        }

        $this->entityManager->remove($intervention);
        $this->entityManager->flush();
    }

    public function addTacheToIntervention(Tache $tache, string $categorieName): void
    {
        $categorie = $this->categorieRepository->findOneBy(['nomCat' => $categorieName]);
        if (!$categorie) {
            throw new \Exception('Category not found');
        }

        $tache->setIdCat($categorie);
        $tache->setStatutTache('ouvert');

        $this->entityManager->persist($tache);
        $this->entityManager->flush();
    }

    public function addCategorieToIntervention(Categorie $categorie, Intervention $intervention, $user): void
    {
        $categorie->setIdInter($intervention);
        $categorie->setCreatedBy($user);

        $this->entityManager->persist($categorie);
        $this->entityManager->flush();
    }
}
