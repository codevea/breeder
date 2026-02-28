<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 3)]
    public string $name = '';

    #[Assert\NotBlank()]
    #[Assert\Email()]
    public string $email = '';

    #[Assert\Length(min: 10)]
    public string $content = '';

    #[Assert\NotBlank()]
    #[Assert\IsTrue()]
    public ?bool $agreeTerms = null;
}
