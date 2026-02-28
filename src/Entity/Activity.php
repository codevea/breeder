<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Stringable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Cette activité existe déjà.')]
class Activity implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'L\'activité doit être renseigner')]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['name'])]
    #[Assert\NotBlank]
    private ?string $slug = null;

    /**
     * @var Collection<int, BusinessPage>
     */
    #[ORM\OneToMany(targetEntity: BusinessPage::class, mappedBy: 'activity')]
    private Collection $businessPages;

    public function __construct()
    {
        $this->businessPages = new ArrayCollection();
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

    public function __toString(): string
    {
        return $this->name;
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
            $businessPage->setActivity($this);
        }

        return $this;
    }

    public function removeBusinessPage(BusinessPage $businessPage): static
    {
        if ($this->businessPages->removeElement($businessPage)) {
            // set the owning side to null (unless already changed)
            if ($businessPage->getActivity() === $this) {
                $businessPage->setActivity(null);
            }
        }

        return $this;
    }
}
