<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\AffixeRegistrationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Stringable;

#[ORM\Entity(repositoryClass: AffixeRegistrationRepository::class)]
#[UniqueEntity(fields: ['officialRegister'], message: "Ce livre est déjà enregistrée dans la base de données.")]
class AffixeRegistration implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Veuillez spécifier le livre officiel d’enregistrement.")]
    private ?string $officialRegister = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Slug(fields: ['officialRegister'])]
    private ?string $slug = null;

    /**
     * @var Collection<int, Affixe>
     */
    #[ORM\OneToMany(targetEntity: Affixe::class, mappedBy: 'affixeRegistration')]
    private Collection $affixes;

    #[ORM\Column(length: 255)]
    private ?string $species = null;


    public function __construct()
    {
        $this->affixes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOfficialRegister(): ?string
    {
        return $this->officialRegister;
    }

    public function setOfficialRegister(string $officialRegister): static
    {
        $this->officialRegister = $officialRegister;

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
     * @return Collection<int, Affixe>
     */
    public function getAffixes(): Collection
    {
        return $this->affixes;
    }

    public function addAffix(Affixe $affix): static
    {
        if (!$this->affixes->contains($affix)) {
            $this->affixes->add($affix);
            $affix->setAffixeRegistration($this);
        }

        return $this;
    }

    public function removeAffix(Affixe $affix): static
    {
        if ($this->affixes->removeElement($affix)) {
            // set the owning side to null (unless already changed)
            if ($affix->getAffixeRegistration() === $this) {
                $affix->setAffixeRegistration(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->officialRegister;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;

        return $this;
    }
}
