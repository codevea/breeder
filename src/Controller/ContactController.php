<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactDTOFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailerInterface): Response
    {
        $data = new ContactDTO();
        $form = $this->createForm(ContactDTOFormType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $email = (new TemplatedEmail())
                    ->from('nathalie.vrecq@gmail.com')
                    ->to($data->email)
                    ->subject('Demande de contact')
                    ->htmlTemplate('contact/emails/email_contact.html.twig')
                    ->locale('fr')
                    ->context(['data' => $data]);

                $mailerInterface->send($email);
                $this->addFlash('success', 'Votre message à bien été envoyer');
                return $this->redirectToRoute('app_contact');
            } catch (\Throwable $th) {
                $this->addFlash('danger', 'Imposible d\envoyer votre e-mail');
            }
        }

        return $this->render('contact/contact.html.twig', [
            'formContact' => $form->createView(),
        ]);
    }
}
