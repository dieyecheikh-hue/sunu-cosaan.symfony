<?php

namespace App\Controller\Admin\Comment;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class CommentController extends AbstractController
{
    #[Route('/comments', name: 'app_admin_comments', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('pages/admin/comments/index.html.twig', [
            'comments' => $comments,
        ]);
    }
}
