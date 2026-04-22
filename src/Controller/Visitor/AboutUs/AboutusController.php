<?php

namespace App\Controller\Visitor\AboutUs;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AboutusController extends AbstractController
{
    #[Route('/aboutus', name: 'app_visitor_aboutus', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/visitor/aboutus/index.html.twig');
    }
}
