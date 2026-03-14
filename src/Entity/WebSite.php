<?php

namespace App\Entity;

use App\Repository\WebSiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebSiteRepository::class)]
class WebSite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    /**
     * @var Collection<int, Breeder>
     */
    #[ORM\OneToMany(targetEntity: Breeder::class, mappedBy: 'webSite')]
    private Collection $breeders;

    public function __construct()
    {
        $this->breeders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

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
            $breeder->setWebSite($this);
        }

        return $this;
    }

    public function removeBreeder(Breeder $breeder): static
    {
        if ($this->breeders->removeElement($breeder)) {
            // set the owning side to null (unless already changed)
            if ($breeder->getWebSite() === $this) {
                $breeder->setWebSite(null);
            }
        }

        return $this;
    }
}
