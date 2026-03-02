<?php

namespace App\Twig\Components;

use App\Entity\BusinessPage;
use App\Entity\Phone;
use App\Form\BusinessPageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
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

    public function mount(?BusinessPage $initialBreeder = null): void
    {
        $this->initialBreeder = $initialBreeder ?? new BusinessPage();
        
        // On s'assure que l'entité a toujours au moins un téléphone au début
        if ($this->initialBreeder->getPhone()->isEmpty()) {
            $this->initialBreeder->addPhone(new Phone());
        }
    }

    protected function instantiateForm(): FormInterface
    {       
        // Si le SIRET est rempli dans le formulaire mais que la collection phone 
        // n'a pas encore été initialisée dans formValues, on crée la première ligne.
        if (
            isset($this->formValues['siret']) && 
            $this->formValues['siret'] !== '' && 
            (!isset($this->formValues['phone']) || count($this->formValues['phone']) === 0)
        ) {
            $this->formValues['phone'] = [[]]; // Crée une entrée vide pour la collection
        }

        return $this->createForm(BusinessPageFormType::class, $this->initialBreeder);
    }

    #[LiveAction]
    public function addPhone(): void
    {
        $this->formValues['phone'][] = [];
    }

#[LiveAction]
public function removePhone(#[LiveArg] int $index): void
{
    // On compte combien il y a de téléphones actuellement dans les valeurs du formulaire
    $phoneCount = count($this->formValues['phone'] ?? []);

    // Sécurité : si c'est le dernier, on ne fait rien (ou on pourrait envoyer un message flash)
    if ($phoneCount <= 1) {
        // Optionnel : $this->addFlash('warning', 'Au moins un numéro est requis.');
        $this->addFlash('warning', 'Au moins un numéro est requis.');
        return; 
    }

    unset($this->formValues['phone'][$index]);
    
    // Après un unset, il est conseillé de "ré-indexer" le tableau pour éviter des clés manquantes
    $this->formValues['phone'] = array_values($this->formValues['phone']);
}
}