<?php

namespace App\Entity;

use App\Repository\PetRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PetRepository::class)]
class Pet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\OneToOne(inversedBy: 'pet', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Icad $icad = null;

    #[ORM\ManyToOne(inversedBy: 'pets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?PetGender $petGender = null;

    #[ORM\ManyToOne(inversedBy: 'pets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Breeder $breeder = null;

    #[ORM\ManyToOne(inversedBy: 'pets')]
    private ?Affixe $affixe = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIcad(): ?Icad
    {
        return $this->icad;
    }

    public function setIcad(Icad $icad): static
    {
        $this->icad = $icad;

        return $this;
    }

    public function getPetGender(): ?PetGender
    {
        return $this->petGender;
    }

    public function setPetGender(?PetGender $petGender): static
    {
        $this->petGender = $petGender;

        return $this;
    }

    public function getBreeder(): ?Breeder
    {
        return $this->breeder;
    }

    public function setBreeder(?Breeder $breeder): static
    {
        $this->breeder = $breeder;

        return $this;
    }

    public function getAffixe(): ?Affixe
    {
        return $this->affixe;
    }

    public function setAffixe(?Affixe $affixe): static
    {
        $this->affixe = $affixe;

        return $this;
    }

}
