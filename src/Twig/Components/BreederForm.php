<?php

namespace App\Twig\Components;

use App\Entity\Breeder;
use App\Entity\BusinessPage;
use App\Form\BreederFormType;
use App\Repository\AffixeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(csrf: true)] // Ajoute (csrf: true) ici false -> dans BreederFormType
final class BreederForm extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public ?Breeder $initialFormData = null;

    #[LiveProp]
    public BusinessPage $businessPage; // On stocke la page business

    #[LiveProp]
    public ?string $type = null;

    protected function instantiateForm(): FormInterface
    {
        // Si on n'a pas de données initiales, on crée un nouveau Breeder lié à la page
        $breeder = $this->initialFormData ?? (new Breeder())->setBusinessPage($this->businessPage);

        $currentType = $this->type ?? ($this->businessPage->getActivity()->getSlug() === 'eleveur-de-chat' ? 'cat' : 'dog');

        return $this->createForm(BreederFormType::class, $breeder, [
            'breeder_type' => $currentType,
            'validation_groups' => [$currentType === 'cat' ? 'eleveur-de-chat' : 'eleveur-de-chien'],
        ]);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $em, AffixeRepository $affixeRepository)
    {
        // SI TU VOIS CECI, LE BOUTON FONCTIONNE ENFIN
        // dd('Action SAVE atteinte !');
        // 1. On soumet le formulaire
        $this->submitForm();

        /** @var Breeder $breeder */
        $breeder = $this->getForm()->getData();
        $isCat = $this->type === 'cat';

        // 2. Logique "NE PAS EFFACER" : Vérification de l'affixe
        $affixe = $breeder->getAffixe();
        if ($affixe && $affixe->getId() === null) {
            $existingAffixe = $affixeRepository->findOneBy(['name' => $affixe->getName()]);
            if ($existingAffixe) {
                $this->addFlash('warning', 'Cet affixe existe déjà. Veuillez en enregistrer un autre ou le sélectionner dans la liste.');
                return $this->redirectToRoute('app_profile_business_page_index', ['id' => $this->businessPage->getId()]);
            }
        }

        // Note : La vérification du doublon de race est gérée automatiquement 
        // par l'attribut #[UniqueEntity] de ton entité Breeder lors du submitForm().
        // Si le formulaire n'est pas valide, l'action s'arrête ici.
        if (!$this->getForm()->isValid()) {
            return null;
        }

        try {
            $em->persist($breeder);
            $em->flush();

            $this->addFlash(
                'success',
                $isCat
                    ? 'La race de chat a bien été ajoutée.'
                    : 'La race de chien a bien été ajoutée.'
            );

            return $this->redirectToRoute('app_profile_business_page_index', [
                'id' => $this->businessPage->getId()
            ]);
        } catch (\Throwable $th) {
            $this->addFlash('danger', 'Une erreur est survenue lors de l’enregistrement.');
            return $this->redirectToRoute('app_profile_business_page_index', ['id' => $this->businessPage->getId()]);
        }
    }

    #[LiveAction]
    public function validate(): void
    {
        // TEMPORAIRE : Si tu vois cet écran blanc avec ce texte, 
        // alors le bouton fonctionne et c'est la validation qui bloque après.
        // dd('L\'action save est bien atteinte !');

        // On soumet le formulaire virtuellement pour déclencher 
        // les contraintes de validation (UniqueEntity, etc.) en temps réel.
        $this->submitForm();

        // AJOUTE CECI TEMPORAIREMENT :
        // if (!$this->getForm()->isValid()) {
        //     // Cela va afficher toutes les erreurs de validation dans ton dump
        //     dd($this->getForm()->getErrors(true, true)); 
        // }
    }
}
