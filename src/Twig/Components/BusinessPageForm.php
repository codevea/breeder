<?php

namespace App\Twig\Components;

use App\Entity\BusinessPage;
use App\Form\BusinessPageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class BusinessPageForm extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public ?BusinessPage $initialBreeder = null;

    protected function instantiateForm(): FormInterface
    {
        // On passe l'entité si elle existe déjà
        return $this->createForm(BusinessPageFormType::class, $this->initialBreeder);
    }
}
