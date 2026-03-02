<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\RaceCat;
use App\Repository\BreederRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BreederRepository::class)]
#[UniqueEntity(
    fields: ['raceCat', 'businessPage'],
    message: 'Cette race de chat est déjà enregistrée...',
    errorPath: 'raceCat', // <--- erreur liée au champ raceCat
    groups: ['eleveur-de-chat']
)]
#[UniqueEntity(
    fields: ['raceDog', 'businessPage'],
    message: 'Cette race de chien est déjà enregistrée...',
    errorPath: 'raceDog', // <--- erreur liée au champ raceDog
    groups: ['eleveur-de-chien']
)]
class Breeder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull(groups: ['eleveur-de-chat'])]
    #[Assert\Valid(groups: ['eleveur-de-chat'])]
    #[ORM\ManyToOne(inversedBy: 'breeders')]
    #[ORM\JoinColumn(nullable: true)]
    private ?RaceCat $raceCat = null;

    #[Assert\NotNull(groups: ['eleveur-de-chien'])]
    #[Assert\Valid(groups: ['eleveur-de-chien'])]
    #[ORM\ManyToOne(inversedBy: 'breeders')]
    #[ORM\JoinColumn(nullable: true)]
    private ?RaceDog $raceDog = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'breeders', cascade: ['persist'])]
    #[Assert\Valid(groups: ['eleveur-de-chien', 'eleveur-de-chat'])]
    private ?Affixe $affixe = null;

    #[ORM\ManyToOne(inversedBy: 'breeders')]
    #[Assert\Valid(groups: ['eleveur-de-chien', 'eleveur-de-chat'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?BusinessPage $businessPage = null;

    /**
     * @var Collection<int, Pet>
     */
    #[ORM\OneToMany(targetEntity: Pet::class, mappedBy: 'breeder', orphanRemoval: true)]
    private Collection $pets;

    public function __construct()
    {
        $this->pets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaceCat(): ?RaceCat
    {
        return $this->raceCat;
    }

    public function setRaceCat(?RaceCat $raceCat): static
    {
        $this->raceCat = $raceCat;

        if ($raceCat !== null) {
            $this->slug = $raceCat->getSlug();
        }

        return $this;
    }

    public function getRaceDog(): ?RaceDog
    {
        return $this->raceDog;
    }

    public function setRaceDog(?RaceDog $raceDog): static
    {
        $this->raceDog = $raceDog;

        if ($raceDog !== null) {
            $this->slug = $raceDog->getSlug();
        }

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


    public function getAffixe(): ?Affixe
    {
        return $this->affixe;
    }

    public function setAffixe(?Affixe $affixe): static
    {
        $this->affixe = $affixe;

        return $this;
    }

    public function getBusinessPage(): ?BusinessPage
    {
        return $this->businessPage;
    }

    public function setBusinessPage(?BusinessPage $businessPage): static
    {
        $this->businessPage = $businessPage;

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
            $pet->setBreeder($this);
        }

        return $this;
    }

    public function removePet(Pet $pet): static
    {
        if ($this->pets->removeElement($pet)) {
            // set the owning side to null (unless already changed)
            if ($pet->getBreeder() === $this) {
                $pet->setBreeder(null);
            }
        }

        return $this;
    }
}
