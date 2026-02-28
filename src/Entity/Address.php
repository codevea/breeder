<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[UniqueEntity(fields: ['street', 'zipCode', 'city', 'country'], message: 'Cette adresse existe déjà.')]
class Address implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    /**
     * @var Collection<int, BusinessPage>
     */
    #[ORM\OneToMany(targetEntity: BusinessPage::class, mappedBy: 'address')]
    private Collection $businessPages;

    public function __construct()
    {
        $this->businessPages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

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
            $businessPage->setAddress($this);
        }

        return $this;
    }

    public function removeBusinessPage(BusinessPage $businessPage): static
    {
        if ($this->businessPages->removeElement($businessPage)) {
            // set the owning side to null (unless already changed)
            if ($businessPage->getAddress() === $this) {
                $businessPage->setAddress(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->street . '<br>' . $this->zipCode . ' ' . $this->city . '<br>' .  $this->country;
    }
}
