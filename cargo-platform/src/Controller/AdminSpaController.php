<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminSpaController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin-spa', name: 'admin_spa')]
    public function index(): Response
    {
        return $this->render('admin/spa.html.twig');
    }
}