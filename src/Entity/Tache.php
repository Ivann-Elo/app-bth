<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    private ?string $statut_tache = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Categorie $id_cat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatutTache(): ?string
    {
        return $this->statut_tache;
    }

    public function setStatutTache(string $statut_tache): static
    {
        $this->statut_tache = $statut_tache;

        return $this;
    }

    public function getIdCat(): ?Categorie
    {
        return $this->id_cat;
    }

    public function setIdCat(?Categorie $id_cat): static
    {
        $this->id_cat = $id_cat;

        return $this;
    }
}
