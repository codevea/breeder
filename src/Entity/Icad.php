<?php

namespace App\Entity;

use App\Repository\IcadRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IcadRepository::class)]
class Icad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\OneToOne(mappedBy: 'icad')]
    private ?Pet $pet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getPet(): ?Pet
    {
        return $this->pet;
    }

    public function setPet(Pet $pet): static
    {
        // set the owning side of the relation if necessary
        if ($pet->getIcad() !== $this) {
            $pet->setIcad($this);
        }

        $this->pet = $pet;

        return $this;
    }
}
