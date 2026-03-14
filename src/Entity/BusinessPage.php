<?php

declare(strict_types=1);

namespace App\Entity;


use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BusinessPageRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BusinessPageRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_user_activity', columns: ['user_id', 'activity_id'])] //  verrouiller la base de données
#[UniqueEntity(fields: ['activity', 'user'], errorPath: 'activity', message: 'Vous avez déjà sélectionné cette activité.')]
#[Assert\Cascade]
class BusinessPage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'businessPage')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'businessPages', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez fournir une adresse.')]
    private ?Address $address = null;

    #[ORM\ManyToOne(inversedBy: 'businessPages', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez fournir votre numéro de SIRET.')]
    private ?Siret $siret = null;

    /**
     * @var Collection<int, Breeder>
     */
    #[ORM\OneToMany(targetEntity: Breeder::class, mappedBy: 'businessPage', cascade: ['remove'])]
    private Collection $breeders;

    #[ORM\ManyToOne(inversedBy: 'businessPages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez sélectionner une activité.')]
    private ?Activity $activity = null;

    /**
     * @var Collection<int, Phone>
     */
    #[ORM\OneToMany(targetEntity: Phone::class, mappedBy: 'businessPage', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Count(min: 1, max: 2, minMessage: "Vous devez ajouter au moins un numéro de téléphone.", maxMessage: "Vous ne pouvez pas ajouter plus de deux numéro de téléphone.")]
    private Collection $phone;


    public function __construct()
    {
        $this->breeders = new ArrayCollection();
        $this->phone = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getSiret(): ?Siret
    {
        return $this->siret;
    }

    public function setSiret(?Siret $siret): static
    {
        $this->siret = $siret;

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
            $breeder->setBusinessPage($this);
        }

        return $this;
    }

    public function removeBreeder(Breeder $breeder): static
    {
        if ($this->breeders->removeElement($breeder)) {
            // set the owning side to null (unless already changed)
            if ($breeder->getBusinessPage() === $this) {
                $breeder->setBusinessPage(null);
            }
        }

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return Collection<int, Phone>
     */
    public function getPhone(): Collection
    {
        return $this->phone;
    }

    public function addPhone(Phone $phone): static
    {
        if (!$this->phone->contains($phone)) {
            $this->phone->add($phone);
            $phone->setBusinessPage($this);
        }

        return $this;
    }

    public function removePhone(Phone $phone): static
    {
        if ($this->phone->removeElement($phone)) {
            // set the owning side to null (unless already changed)
            if ($phone->getBusinessPage() === $this) {
                $phone->setBusinessPage(null);
            }
        }

        return $this;
    }
}
