<?php

namespace App\Twig\Components;

use App\Entity\Breeder;
use App\Entity\BusinessPage;
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
    public function save(EntityManagerInterface $em)
    {
        //  soumet le formulaire
        $this->submitForm();

        /** @var Breeder $breeder */
        $breeder = $this->getForm()->getData();
        $isCat = $this->type === 'cat';

        // Si le formulaire n'est pas valide
        if (!$this->getForm()->isValid()) {
            return null;
        }

        try {
            $em->persist($breeder);
            $em->flush();

            $this->addFlash('success', $isCat ? 'La race de chat a bien été ajoutée.' : 'La race de chien a bien été ajoutée.');
            return $this->redirectToRoute('app_profile_business_page_index', ['id' => $this->businessPage->getId()]);
        } catch (\Throwable $th) {

            $this->addFlash('danger', 'Une erreur est survenue : ' . $th->getMessage());
            return $this->redirectToRoute('app_profile_business_page_index', ['id' => $this->businessPage->getId()]);
        }
    }

    #[LiveAction]
    public function validate(): void
    {
        $this->submitForm();
    }
}
