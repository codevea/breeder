<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhoneRepository::class)]
#[UniqueEntity(fields: ['businessPage', 'number'],  message: 'Vous avez déjà ce uméro de téléphone d\'enregister.')]
class Phone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le numéro est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^[0-9]{10}$/",
        message: "Votre numéro doit comporter 10 chiffres (ex: 0612345678)."
    )]
    private ?string $number = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'phone')]
    private ?BusinessPage $businessPage = null;


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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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
}
