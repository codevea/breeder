<?php

namespace App\Twig\Components;

use App\Entity\Breeder;
use App\Form\BreederFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class BreederForm extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public ?Breeder $initialFormData = null;

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager)
    {
        // $this->initialFormData ne contiendra *pas encore* les données mises à jour !
        // soumettre le formulaire
        $this->submitForm();
        // maintenant, vous pouvez accéder aux données mises à jour
        $breeder = $this->getForm()->getData();
        // (identique à ci-dessus)
        $breeder = $this->initialFormData;
        $this->resetForm();
    }

    protected function instantiateForm(): FormInterface
    {
        // On passe l'entité si elle existe déjà
        return $this->createForm(BreederFormType::class, $this->initialFormData);
    }
}
