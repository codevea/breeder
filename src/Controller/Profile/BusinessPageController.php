<?php

namespace App\Controller\Profile;

use App\Entity\Breeder;
use App\Entity\BusinessPage;
use App\Entity\User;
use App\Form\BreederFormType;
use App\Form\BusinessPageFormType;
use App\Repository\AffixeRepository;
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
    public function new(BusinessPageRepository $businessPageRepository, Request $request, EntityManagerInterface $em): Response
    {

        /** @var User|null $user */
        $user = $this->getUser();
        $businessPages = $businessPageRepository->findBy(['user' => $user]);

        $businessPage = new BusinessPage();

        $form = $this->createForm(BusinessPageFormType::class, $businessPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                // Associer l'utilisateur
                $businessPage->setUser($user);

                // Vérification d’un doublon (user + activity)
                $exists = $em->getRepository(BusinessPage::class)->findOneBy([
                    'user'     => $user,
                    'activity' => $businessPage->getActivity(),
                ]);

                if ($exists) {
                    $this->addFlash('warning', 'Cette activité est déjà liée à votre compte.');
                    return $this->redirectToRoute('app_profile_business_page_new');
                }
                // Enregistrement
                $em->persist($businessPage);
                $em->flush();

                $this->addFlash('success', 'Votre activité a bien été enregistrée.');
                return $this->redirectToRoute('app_profile_index');
            } catch (\Throwable $th) {

                // Log interne (Monolog)
                // $this->logger->error($e->getMessage());
                $this->addFlash('danger', 'Une erreur est survenue lors de l’enregistrement.');
                return $this->redirectToRoute('app_profile_business_page_new');
            }
        }

        return $this->render('profile/business_page/business_page_new.html.twig', [
            'formBusinessPageCreate' => $form->createView(),
            'businessPages'          => $businessPages,
            'user'                   => $user,
        ]);
    }

    #[Route('/edition-activite/{id}', name: 'index', methods: [Request::METHOD_POST, Request::METHOD_GET])]
    #[IsGranted('ROLE_USER')]
    public function index(BusinessPageRepository $businessPageRepository, BusinessPage $businessPage, Request $request, EntityManagerInterface $em, AffixeRepository $affixeRepository,): Response {

        /** @var User|null $user */
        $user = $this->getUser();
        $businessPages = $businessPageRepository->findBy(['user' => $user]);

        $breeder = new Breeder();
        $breeder->setBusinessPage($businessPage);

        $slug = $businessPage->getActivity()->getSlug();

        // Déterminer le type d’éleveur (chat ou chien)
        $type = $slug === 'eleveur-de-chat' ? 'cat' : 'dog';

        // Formulaire unique
        $form = $this->createForm(BreederFormType::class, $breeder, [
            'breeder_type' => $type,
            'validation_groups' => [$type === 'cat' ? 'eleveur-de-chat' : 'eleveur-de-chien'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Vérifie si un affixe identique existe déjà à la création
            $affixe = $breeder->getAffixe();

            if ($affixe && $affixe->getId() === null) {

                $existingAffixe = $affixeRepository->findOneBy([
                    'name' => $affixe->getName(), // ou autre champ unique selon ton entité Affixe
                ]);

                if ($existingAffixe) {
                    $this->addFlash('warning', 'Cet affixe existe déjà. Veuillez en choisir un autre ou le sélectionner dans la liste.');
                    return $this->redirectToRoute('app_profile_business_page_index', [
                        'id' => $businessPage->getId()
                    ]);
                }
            }

            $criteria = [
                $type === 'cat' ? 'raceCat' : 'raceDog' => $type === 'cat'
                    ? $breeder->getRaceCat()
                    : $breeder->getRaceDog(),
                'businessPage' => $businessPage,
            ];

            $exists = $em->getRepository(Breeder::class)->findOneBy($criteria);

            if ($exists) {
                $this->addFlash(
                    'warning',
                    $type === 'cat'
                        ? 'Cette race de chat est déjà liée à votre élevage.'
                        : 'Cette race de chien est déjà liée à votre élevage.'
                );

                return $this->redirectToRoute('app_profile_business_page_index', [
                    'id' => $businessPage->getId()
                ]);
            }

            // Enregistrement
            $em->persist($breeder);
            $em->flush();

            $this->addFlash(
                'success',
                $type === 'cat'
                    ? 'La race de chat que vous élevez a bien été ajoutée et associée à l\'affixe.'
                    : 'La race de chien que vous élevez a bien été ajoutée et associée à l\'affixe.'
            );

            return $this->redirectToRoute('app_profile_business_page_index', [
                'id' => $businessPage->getId()
            ]);
        }

        return $this->render('profile/business_page/business_page_index.html.twig', [
            'user' => $user,
            'businessPage' => $businessPage,
            'businessPages' => $businessPages,
            'breeder' => $breeder,
            'formBreeder' => $form,
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
