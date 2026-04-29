<?php

namespace App\Controller\Admin\Home;

use App\Repository\CommentRepository;
use App\Repository\ContactRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_admin_home', methods: ['GET'])]
    public function index(
        PostRepository $postRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository,
        ContactRepository $contactRepository,
    ): Response {
        return $this->render('pages/admin/home/index.html.twig', [
            'totalPosts' => count($postRepository->findBy(['isPublished' => true])),
            'totalUsers' => count($userRepository->findAll()),
            'totalComments' => count($commentRepository->findAll()),
            'totalContacts' => count($contactRepository->findAll()),
        ]);
    }
}
