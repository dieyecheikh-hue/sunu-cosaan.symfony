<?php

namespace App\Controller\User\Home;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/user/home', name: 'app_user_home', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy(
            ['isPublished' => true],
            ['createdAt' => 'DESC']
        );

        return $this->render('pages/user/home/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/user/show/{slug}', name: 'app_user_show_show', methods: ['GET'])]
    public function show(string $slug, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBy(['slug' => $slug, 'isPublished' => true]);

        if (!$post) {
            throw $this->createNotFoundException('Article introuvable.');
        }

        return $this->render('pages/user/show/show.html.twig', [
            'post' => $post,
        ]);
    }
}
