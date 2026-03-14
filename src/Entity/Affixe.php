<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AffixeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AffixeRepository::class)]
#[UniqueEntity(
    fields: ['name'],
    message: "Cet affixe existe déjà. Veuillez en choisir un autre ou le sélectionner dans la liste.",
    groups: ['Default', 'eleveur-de-chat', 'eleveur-de-chien']
)]
#[UniqueEntity(fields: ['slug'], message: "Cette affixe est déjà enregistrée dans la base de données.")]
class Affixe implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Vous devez saisir un nom d\'affixe')]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['name'])]
    private ?string $slug = null;

    /**
     * @var Collection<int, Breeder>
     */
    #[ORM\OneToMany(targetEntity: Breeder::class, mappedBy: 'affixe')]
    private Collection $breeders;

    #[ORM\ManyToOne(inversedBy: 'affixes')]
    #[Assert\Valid]
    private ?AffixeRegistration $affixeRegistration = null;

    /**
     * @var Collection<int, Pet>
     */
    #[ORM\OneToMany(targetEntity: Pet::class, mappedBy: 'affixe')]
    private Collection $pets;

    public function __construct()
    {
        $this->breeders = new ArrayCollection();
        $this->pets = new ArrayCollection();
    }


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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Breeder>
     */
    public function getBreeders(): Collection
    {
        return $this->breeders;
    }

    public function addBreeder(Breeder $breeder): static
    {
        if (!$this->breeders->contains($breeder)) {
            $this->breeders->add($breeder);
            $breeder->setAffixe($this);
        }

        return $this;
    }

    public function removeBreeder(Breeder $breeder): static
    {
        if ($this->breeders->removeElement($breeder)) {
            // set the owning side to null (unless already changed)
            if ($breeder->getAffixe() === $this) {
                $breeder->setAffixe(null);
            }
        }

        return $this;
    }

    public function getAffixeRegistration(): ?AffixeRegistration
    {
        return $this->affixeRegistration;
    }

    public function setAffixeRegistration(?AffixeRegistration $affixeRegistration): static
    {
        $this->affixeRegistration = $affixeRegistration;

        return $this;
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
            $pet->setAffixe($this);
        }

        return $this;
    }

    public function removePet(Pet $pet): static
    {
        if ($this->pets->removeElement($pet)) {
            // set the owning side to null (unless already changed)
            if ($pet->getAffixe() === $this) {
                $pet->setAffixe(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?: '';
    }
}
