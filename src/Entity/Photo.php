<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[Vich\Uploadable]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'photos', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable:false)]
    private ?string $imageName = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Intervention $idInter = null;


    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;


    // ID --  Getter and Setter 
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(string $id): static
    {
        $this->id = $id;
        
        return $this;
    }
    
    // File --  Getter and Setter 
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    
    // ImageName --  Getter and Setter
    public function setImageName(string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
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
