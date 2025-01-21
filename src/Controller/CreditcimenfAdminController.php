<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreditcimenfAdminController extends AbstractController
{
    #[Route('/creditcimenf/admin', name: 'app_creditcimenf_admin')]
    public function index(): Response
    {
        return $this->render('creditcimenf_admin/index.html.twig', [
            'controller_name' => 'CreditcimenfAdminController',
        ]);
    }
}
