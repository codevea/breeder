<?php

namespace App\Controller\Profile\RoleBreederVerified\Pet;

use App\Entity\Breeder;
use App\Entity\Pet;
use App\Entity\User;
use App\Form\PetFormType;
use App\Repository\BusinessPageRepository;
use App\Repository\PetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/profil/eleveur', name: 'app_profile_roleBreederVerified_pet_')]
#[IsGranted('ROLE_USER')]
#[IsGranted('ROLE_BREEDER_VERIFIED')]
final class PetController extends AbstractController
{

    #[Route('/gestion-de-votre-cheptel/{slug:breeder}', name: 'index')]
    #[IsGranted('ROLE_USER')]
    #[IsGranted('ROLE_BREEDER_VERIFIED')]
    public function index(PetRepository $petRepository, Breeder $breeder, BusinessPageRepository $businessPageRepository): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        $businessPages = $businessPageRepository->findBy(['user' => $user]);
        $pets = $petRepository->findBy(['breeder' => $breeder]);

        return $this->render('profile/role_breeder_verified/pet/pet_index.html.twig', [
            'breeder' => $breeder,
            'businessPages' => $businessPages,
            'pets' => $pets,
        ]);
    }


    #[Route('/cheptel-ajouter-animal/{slug:breeder}', name: 'new')]
    #[IsGranted('ROLE_USER')]
    #[IsGranted('ROLE_BREEDER_VERIFIED')]
    public function new(Breeder $breeder, BusinessPageRepository $businessPageRepository, Request $request, EntityManagerInterface $em): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        $businessPages = $businessPageRepository->findBy(['user' => $user]);

        $pet = new Pet();
        $formPet = $this->createForm(PetFormType::class, $pet);
        $formPet->handleRequest($request);

        if ($formPet->isSubmitted() && $formPet->isValid()) {
            $formPet = $pet->setBreeder($breeder);
            $em->persist($formPet);
            $em->flush();

            $this->addFlash('success', 'Votre animal à bien été créer');
            return $this->redirectToRoute('app_profile_roleBreederVerified_pet_new', ['slug' => $breeder->getSlug()]);
        }

        return $this->render('profile/role_breeder_verified/pet/pet_new.html.twig', [
            'businessPages' => $businessPages,
            'formPet' => $formPet,
            'breeder' => $breeder,
        ]);
    }

    #[Route('/edition-animal/{id}', name: 'edit')]
    #[IsGranted('ROLE_USER')]
    #[IsGranted('ROLE_BREEDER_VERIFIED')]
    public function edit(Pet $pet, BusinessPageRepository $businessPageRepository, Request $request, EntityManagerInterface $em): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        $businessPages = $businessPageRepository->findBy(['user' => $user]);

        // $slug = $pet->getBreeder()->getSlug();

        // $formPet = $this->createForm(PetFormType::class, $pet);
        // $formPet->handleRequest($request);

        // if ($formPet->isSubmitted() && $formPet->isValid()) {
        //     $em->persist($pet);
        //     $em->flush();

        //     $this->addFlash('success', 'Les modification de la fiche de votre animal à bien été enregistrer');
        //     return $this->redirectToRoute('app_profile_roleBreederVerified_pet_index', ['slug' => $slug]);
        // }


        return $this->render('profile/role_breeder_verified/pet/pet_edit.html.twig', [
            'businessPages' => $businessPages,
            'pet' => $pet,
            // 'formPet' => $formPet,
        ]);
    }


    #[Route(path: '/suppression-animal/{id}', name: 'delete', methods: ['POST'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_USER')]
    public function delete(Pet $pet, EntityManagerInterface $em, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$pet->getId(), $request->getPayload()->getString('_token'))) {

            $pet->setBreeder(null);
            $em->remove($pet);
            $em->flush();
        }

        $this->addFlash('success', 'Votre élevage à bien été supprimer');
        return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
