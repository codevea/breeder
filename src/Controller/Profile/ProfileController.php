<?php

namespace App\Controller\Profile;

use App\Entity\User;
use App\Repository\BusinessPageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route(path: '/profil', name: 'app_profile_')]
final class ProfileController extends AbstractController
{
    #[Route(path: '', name: 'index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(BusinessPageRepository $businessPageRepository): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        $businessPages = $businessPageRepository->findBy(['user' => $user]);

        return $this->render('profile/profile_index.html.twig', [
            'businessPages' => $businessPages
        ]);
    }


    #[Route(path: '/edition-profil/{id}', name: 'edit', methods: [Request::METHOD_GET], requirements: ['id' => requirement::DIGITS])]
    #[IsGranted('ROLE_USER')]
    public function edit(User $user, BusinessPageRepository $businessPageRepository): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        $businessPages = $businessPageRepository->findBy(['user' => $user]);

        return $this->render('profile/profile_edit.html.twig', [
            'businessPages' => $businessPages,
            'user' => $user,
        ]);
    }


    // #[Route(path: '/suppression/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    // #[IsGranted('ROLE_USER')]
    // public function delete(User $user, EntityManagerInterface $entityManagerInterface)
    // {
    //     $entityManagerInterface->remove($user);
    //     $entityManagerInterface->flush();

    //     $this->addFlash('success', 'Votre compte a bien été supprimé');
    //     return $this->redirectToRoute('app_home');
    // }

    #[Route('/suppression/{id}', name: 'delete', methods: ['POST'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'. $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Votre compte a bien été supprimé');
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
