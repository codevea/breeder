<?php
declare(strict_types=1);


namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet e-mail.')]
#[Assert\Callback('validateBusinessPages')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique:true)]
    #[Assert\Email(message: 'L’adresse e-mail {{ value }} n’est pas une adresse e-mail valide.')]
    #[Assert\NotBlank]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_MEDIUM, message: 'Votre mot de passe est trop faible.')]
    private ?string $password = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isVerified = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\IsTrue(message: 'Vous devez accepter les conditions d’utilisation.')]
    private ?bool $agreeTerms = null;

    #[ORM\Column(type: Types::STRING, length: 20)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['Madame', 'Monsieur'])]
    private ?string $gender = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Votre nom doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Votre nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: "/^[\p{L}\p{M}' -]+$/u",
        message: 'Ce champ contient des caractères non valides.'
    )]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Votre prénom doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Votre prénom ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: "/^[\p{L}\p{M}' -]+$/u",
        message: 'Ce champ contient des caractères non valides.'
    )]
    private ?string $firstName = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Assert\LessThanOrEqual('now', message: 'La date de création ne peut pas être dans le futur.')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Assert\LessThanOrEqual('now', message: 'La date de mise à jour ne peut pas être dans le futur.')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, BusinessPage>
     */
    #[ORM\OneToMany(targetEntity: BusinessPage::class, mappedBy: 'user', orphanRemoval: true, cascade: ['persist'])]
    #[Assert\Valid] // indispensable pour valider chaque BusinessPage
    #[Assert\Count(
        min: 1,
        max: 2,
        minMessage: 'Vous devez ajouter au moins une activité.',
        maxMessage: 'Vous ne pouvez ajouter que deux activités.'
    )]
    private Collection $businessPage;

    #[ORM\Column(type: Types::STRING, length: 9)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 9,
        max: 9,
        exactMessage: 'Le numéro de SIREN doit contenir exactement {{ limit }} chiffres.'
    )]
    #[Assert\Regex(
        pattern: '/^\d{9}$/',
        message: 'Le numéro de SIREN doit contenir uniquement des chiffres.'
    )]
    private ?string $siren = null;

    /**
     * @var Collection<int, Address>
     */
    #[ORM\OneToMany(targetEntity: Address::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $addresses;

    /**
     * @var Collection<int, Siret>
     */
    #[ORM\OneToMany(targetEntity: Siret::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $sirets;


    public function __construct()
    {
        $this->businessPage = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->sirets = new ArrayCollection();
    }


    #[ORM\PrePersist]
    public function initializeTimestamps(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function validateBusinessPages(ExecutionContextInterface $context): void
    {
        $activities = [];

        foreach ($this->businessPage as $bp) {
            $activity = $bp->getActivity();

            if ($activity === null) {
                continue;
            }

            $name = $activity->getId(); // ou getName()

            if (in_array($name, $activities, true)) {
                $context->buildViolation('Vous avez déjà sélectionné cette activité.')
                    ->atPath('businessPage')
                    ->addViolation();
            }

            $activities[] = $name;
        }
    }

    public function validateDates(ExecutionContextInterface $context): void
    {
        if ($this->updatedAt !== null && $this->createdAt !== null) {
            if ($this->updatedAt < $this->createdAt) {
                $context->buildViolation('La date de mise à jour ne peut pas être antérieure à la date de création.')
                    ->atPath('updatedAt')
                    ->addViolation();
            }
        }
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function isAgreeTerms(): ?bool
    {
        return $this->agreeTerms;
    }

    public function setAgreeTerms(bool $agreeTerms): static
    {
        $this->agreeTerms = $agreeTerms;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, BusinessPage>
     */
    public function getBusinessPage(): Collection
    {
        return $this->businessPage;
    }

    public function addBusinessPage(BusinessPage $businessPage): static
    {
        if (!$this->businessPage->contains($businessPage)) {
            $this->businessPage->add($businessPage);
            $businessPage->setUser($this);
        }

        return $this;
    }

    public function removeBusinessPage(BusinessPage $businessPage): static
    {
        if ($this->businessPage->removeElement($businessPage)) {
            // set the owning side to null (unless already changed)
            if ($businessPage->getUser() === $this) {
                $businessPage->setUser(null);
            }
        }

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): static
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): static
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): static
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Siret>
     */
    public function getSirets(): Collection
    {
        return $this->sirets;
    }

    public function addSiret(Siret $siret): static
    {
        if (!$this->sirets->contains($siret)) {
            $this->sirets->add($siret);
            $siret->setUser($this);
        }

        return $this;
    }

    public function removeSiret(Siret $siret): static
    {
        if ($this->sirets->removeElement($siret)) {
            // set the owning side to null (unless already changed)
            if ($siret->getUser() === $this) {
                $siret->setUser(null);
            }
        }

        return $this;
    }
}
