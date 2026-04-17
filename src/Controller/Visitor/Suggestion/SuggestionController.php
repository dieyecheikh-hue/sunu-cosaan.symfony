<?php

namespace App\Controller\Visitor\Suggestion;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SuggestionController extends AbstractController
{
    #[Route('/suggestion', name: 'app_visitor_suggestion_index')]
    public function index(): Response
    {
        return $this->render('pages/visitor/suggestion/index.html.twig', [
            'controller_name' => 'SuggestionController',
        ]);
    }
}
