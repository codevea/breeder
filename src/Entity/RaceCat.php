<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RaceCatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RaceCatRepository::class)]
#[UniqueEntity(fields: ['race'], message: 'Cette race de chat est déjà enregistrer.')]
class RaceCat implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(groups: ['eleveur-de-chat'])]
    #[ORM\Column(length: 255, unique: true)]
    private string $race = '';

    #[Assert\NotBlank(groups: ['eleveur-de-chat'])]
    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['race'])]
    private string $slug = '';

    /**
     * @var Collection<int, Breeder>
     */
    #[ORM\OneToMany(targetEntity: Breeder::class, mappedBy: 'raceCat')]
    private Collection $breeders;

    public function __construct()
    {
        $this->breeders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRace(): string
    {
        return $this->race;
    }

    public function setRace(string $race): static
    {
        $this->race = $race;

        return $this;
    }

    public function getSlug(): string
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
            $breeder->setRaceCat($this);
        }

        return $this;
    }

    public function removeBreeder(Breeder $breeder): static
    {
        if ($this->breeders->removeElement($breeder)) {
            // set the owning side to null (unless already changed)
            if ($breeder->getRaceCat() === $this) {
                $breeder->setRaceCat(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->race;
    }
}
