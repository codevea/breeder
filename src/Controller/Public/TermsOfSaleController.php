<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TermsOfSaleController extends AbstractController
{
    #[Route('/terms/of/sale', name: 'app_terms_of_sale')]
    public function terms_of_sale(): Response
    {
        return $this->render('public/terms_of_sale/terms_of_sale.html.twig');
    }
}
