<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
#[Vich\Uploadable]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'factures', fileNameProperty: 'factureName')]
    private ?File $factureFile = null;

    #[ORM\Column(nullable:false)]
    private ?string $factureName = null;

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

    public function setFactureFile(?File $factureFile = null): void
    {
        $this->factureFile = $factureFile;
        if (null !== $factureFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFactureFile(): ?File
    {
        return $this->factureFile;
    }

    public function setFactureName(string $factureName): void
    {
        $this->factureName = $factureName;
    }

    public function getFactureName(): ?string
    {
        return $this->factureName;
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
