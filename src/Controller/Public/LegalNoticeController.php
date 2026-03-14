<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalNoticeController extends AbstractController
{
    #[Route('/legal/notice', name: 'app_legal_notice')]
    public function legal_notice(): Response
    {
        return $this->render('legal_notice/public/legal_notice.html.twig');
    }
}
