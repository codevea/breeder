<?php


namespace App\Controller\Public;

use App\Repository\BreederRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BreederRepository $breederRepository): Response
    {
        return $this->render('public/home/index.html.twig', [
            'breeders' => $breederRepository->findAll(),
        ]);
    }
}
