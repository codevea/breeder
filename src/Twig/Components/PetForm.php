<?php

namespace App\Twig\Components;

use App\Entity\Pet;
use App\Entity\Breeder;
use App\Form\PetFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class PetForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    /**
     * L'animal en cours de création ou d'édition
     */
    #[LiveProp]
    public ?Pet $initialFormData = null;

    /**
     * On reçoit l'éleveur (Breeder) pour lier l'animal 
     * et déterminer l'espèce (chat/chien)
     */
    #[LiveProp]
    public Breeder $breeder;

    protected function instantiateForm(): FormInterface
    {
        // Si on n'a pas de données initiales, on rattache l'animal à ll'éleveur
        $pet = $this->initialFormData ?? (new Pet())->setBreeder($this->breeder);
        $currentType = $this->type ?? ($this->breeder->getBusinessPage()->getActivity()->getSlug() === 'eleveur-de-chat' ? 'cat' : 'dog');

        return $this->createForm(PetFormType::class, $pet, [
            'breeder_type' => $currentType,
            'validation_groups' => [$currentType === 'cat' ? 'eleveur-de-chat' : 'eleveur-de-chien'],
        ]);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $em)
    {
        // Soumission du formulaire
        $this->submitForm();

        /** @var Pet $pet */
        $pet = $this->getForm()->getData();

        // Si le formulaire n'est pas valide
        if (!$this->getForm()->isValid()) {
            return null;
        }

        try {

            $em->persist($pet);
            $em->flush();

            $this->addFlash('success', 'Votre animal a été enregistré avec succès.');
            // Redirection vers la liste des animaux de l'éleveur
            return $this->redirectToRoute('app_profile_roleBreederVerified_pet_index', [
                'slug' => $this->breeder->getSlug()
            ]);

        } catch (\Throwable $th) {

            // Erreur SQL 
            $this->addFlash('danger', 'Une erreur est survenue : ' . $th->getMessage());
        }
    }

    #[LiveAction]
    public function validate(): void
    {
        $this->submitForm();
    }
}
