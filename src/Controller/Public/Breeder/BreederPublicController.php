<?php

namespace App\Controller\Public\Breeder;

use App\Entity\Breeder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BreederPublicController extends AbstractController
{
    #[Route('/elevage/{slug:breeder}', name: 'app_public_breeder')]
    public function index(Breeder $breeder): Response
    {
        return $this->render('public/breeder/breeder.html.twig', [
            'breeder' => $breeder,
        ]);
    }
}
