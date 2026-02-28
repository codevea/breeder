<?php

namespace App\Entity;

use App\Repository\SiretRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: SiretRepository::class)]
#[UniqueEntity(fields: ['number'], message: 'Ce numéro de SIRET existe déjà.')]
class Siret implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 14, unique: true)]
    private ?string $number = null;

    /**
     * @var Collection<int, BusinessPage>
     */
    #[ORM\OneToMany(targetEntity: BusinessPage::class, mappedBy: 'siret')]
    private Collection $businessPages;

    public function __construct()
    {
        $this->businessPages = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, BusinessPage>
     */
    public function getBusinessPages(): Collection
    {
        return $this->businessPages;
    }

    public function addBusinessPage(BusinessPage $businessPage): static
    {
        if (!$this->businessPages->contains($businessPage)) {
            $this->businessPages->add($businessPage);
            $businessPage->setSiret($this);
        }

        return $this;
    }

    public function removeBusinessPage(BusinessPage $businessPage): static
    {
        if ($this->businessPages->removeElement($businessPage)) {
            // set the owning side to null (unless already changed)
            if ($businessPage->getSiret() === $this) {
                $businessPage->setSiret(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->number ?? '';
    }
}
