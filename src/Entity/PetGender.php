<?php

namespace App\Entity;

use App\Repository\PetGenderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: PetGenderRepository::class)]
class PetGender implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le genre de l\'animal doit être renseigner')]
    private ?string $gender = null;

    /**
     * @var Collection<int, Pet>
     */
    #[ORM\OneToMany(targetEntity: Pet::class, mappedBy: 'petGender')]
    private Collection $pets;

    public function __construct()
    {
        $this->pets = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function __toString(): string
    {
        return $this->gender;
    }

    /**
     * @return Collection<int, Pet>
     */
    public function getPets(): Collection
    {
        return $this->pets;
    }

    public function addPet(Pet $pet): static
    {
        if (!$this->pets->contains($pet)) {
            $this->pets->add($pet);
            $pet->setPetGender($this);
        }

        return $this;
    }

    public function removePet(Pet $pet): static
    {
        if ($this->pets->removeElement($pet)) {
            // set the owning side to null (unless already changed)
            if ($pet->getPetGender() === $this) {
                $pet->setPetGender(null);
            }
        }

        return $this;
    }
}
