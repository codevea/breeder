<?php

namespace App\Controller\Profile;

use App\Entity\BusinessPage;
use App\Entity\User;
use App\Repository\BusinessPageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route(path: '/profil', name: 'app_profile_business_page_')]
final class BusinessPageController extends AbstractController
{
    #[Route('/creation-activite', name: 'new', methods: [Request::METHOD_POST, Request::METHOD_GET])]
    #[IsGranted('ROLE_USER')]
    public function new(BusinessPageRepository $businessPageRepository): Response
    {
        /** @var User|null $user */
        $user = $this->getUser();
        $businessPages = $businessPageRepository->findBy(['user' => $user]);

        return $this->render('profile/business_page/business_page_new.html.twig', [
            'businessPages'          => $businessPages,
            'user'                   => $user,
        ]);
    }


    #[Route('/edition-activite/{id}', name: 'index', methods: [Request::METHOD_POST, Request::METHOD_GET])]
    #[IsGranted('ROLE_USER')]
    public function index(
        BusinessPageRepository $businessPageRepository,
        BusinessPage $businessPage
    ): Response {

        return $this->render('profile/business_page/business_page_index.html.twig', [
            'user' => $this->getUser(),
            'businessPage' => $businessPage,
            'businessPages' => $businessPageRepository->findBy(['user' => $this->getUser()]),
        ]);
    }


    #[Route(path: '/suppression-activite/{id}', name: 'delete', methods: ['POST'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_USER')]
    public function delete(BusinessPage $businessPage, Request $request, EntityManagerInterface $em)
    {
        if ($this->isCsrfTokenValid('delete' . $businessPage->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($businessPage);
            $em->flush();
        }
        $this->addFlash('success', 'Votre siége social à bien été supprimée');
        return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
