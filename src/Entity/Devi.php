<?php

namespace App\Entity;

use App\Repository\DeviRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DeviRepository::class)]
#[Vich\Uploadable]
class Devi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'devis', fileNameProperty: 'deviName')]
    private ?File $deviFile = null;

    #[ORM\Column(nullable:true)]
    private ?string $deviName = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE' )]
    private ?Intervention $idInter = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setDeviFile(?File $deviFile = null): void
    {
        $this->deviFile = $deviFile;
        if (null !== $deviFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getDeviFile(): ?File
    {
        return $this->deviFile;
    }

    public function setDeviName(string $deviName): void
    {
            $this->deviName = $deviName;
    
    }

    public function getDeviName(): ?string
    {
        return $this->deviName;
    }
    
    public function getIdInter(): ?Intervention
    {
        return $this->idInter;
    }

    public function setIdInter(?Intervention $idInter): static
    {
        $this->idInter = $idInter;

        return $this;
    }
}
