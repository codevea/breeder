<?php


namespace App\Controller\Profile;

use App\Entity\Breeder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route(path: '/profil', name: 'app_profile_breeder_')]
final class BreederController extends AbstractController
{
    #[Route(path: '/suppression-elevage/{id}', name: 'delete', methods: ['POST'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted('ROLE_USER')]
    public function delete(Breeder $breeder, EntityManagerInterface $em, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$breeder->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($breeder);
            $em->flush();
        }

        $this->addFlash('success', 'La race associé a votre élevage à bien été supprimer');
        return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
