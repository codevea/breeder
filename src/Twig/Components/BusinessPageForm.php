<?php

namespace App\Twig\Components;

use App\Entity\BusinessPage;
use App\Entity\Phone;
use App\Form\BusinessPageFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
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

    #[LiveProp(fieldName: 'data')] // Le 'data' permet de garder l'état de l'objet non-persisté
    public ?BusinessPage $initialFormData = null;

    public function mount(?BusinessPage $initialBreeder = null): void
    {
        $this->initialFormData = $initialBreeder ?? new BusinessPage();


        // On s'assure que l'entité a toujours au moins un téléphone au début
        if ($this->initialFormData->getPhone()->isEmpty()) {
            $this->initialFormData->addPhone(new Phone());
        }
    }

    protected function instantiateForm(): FormInterface
    {

        // Si le SIRET est rempli dans le formulaire mais que la collection phone 
        // n'a pas encore été initialisée dnas formValues, on crée la première ligne.
        if (
            isset($this->formValues['siret']) &&
            $this->formValues['siret'] !== '' &&
            (!isset($this->formValues['phone']) || count($this->formValues['phone']) === 0)
        ) {
            $this->formValues['phone'] = [[]]; // Crée une entrée vide pour la collection
        }
        return $this->createForm(BusinessPageFormType::class, $this->initialFormData, [
            'user' => $this->getUser(), // On passe l'utilisateur ici
        ]);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $em)
    {
        $this->submitForm();

        /** @var BusinessPage $businessPage*/
        $businessPage = $this->getForm()->getData();

        // 1. Pour la page business elle-même
        if ($businessPage->getUser() === null) {
            $businessPage->setUser($this->getUser());
        }

        // 2. Pour l'adresse : on vérifie d'abord si l'objet adresse existe
        if ($businessPage->getAddress() && $businessPage->getAddress()->getUser() === null) {
            $businessPage->getAddress()->setUser($this->getUser());
        }

        // 3. Pour le SIRET : idem
        if ($businessPage->getSiret() && $businessPage->getSiret()->getUser() === null) {
            $businessPage->getSiret()->setUser($this->getUser());
        }

        $existingPage = $em->getRepository(BusinessPage::class)->findOneBy([
            'user' => $this->getUser(),
            'activity' => $businessPage->getActivity(),
        ]);

        if ($existingPage && $existingPage->getId() !== $businessPage->getId()) {
            // On ajoute l'erreur directement au champ 'activity' du formulaire
            $this->getForm()->get('activity')->addError(new FormError('Cette activité est déjà liée à votre compte.'));

            // On ne fait rien d'autre. Le composant va se re-rendre et 
            // form_errors(form.activity) affichera le message !
            return;
        }

        try {
            $em->persist($businessPage);
            $em->flush();

            $this->addFlash('success', 'Votre activité a bien été enregistrée.');
            return $this->redirectToRoute('app_profile_index');
            $this->resetForm();
        } catch (\Throwable $th) {

            // $this->addFlash('danger', 'Erreur base de données : ' . $th->getMessage());
            $this->addFlash('danger', 'Une erreur est survenue lors de l’enregistrement.');
            return $this->redirectToRoute('app_profile_business_page_new');
        }
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
        // Sécurité : si c'est le dernier, on ne fait rien 
        if ($phoneCount <= 1) {
            $this->addFlash('warning', 'Au moins un numéro est requis.');
            return;
        }
        if ($phoneCount >= 3) {
            $this->addFlash('warning', 'Vous ne pouvez pas ajouter plus de deux numéro de téléphone.');
            return;
        }

        unset($this->formValues['phone'][$index]);

        // "ré-indexer" le tableau pour éviter des clés manquantes
        $this->formValues['phone'] = array_values($this->formValues['phone']);
    }
}
