<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use App\Form\Admin\PostFormType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class PostController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly PostRepository $postRepository,
    ) {
    }

    #[Route('/post/list', name: 'app_admin_post_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('pages/admin/post/index.html.twig', [
            'posts' => $this->postRepository->findAll(),
        ]);
    }

    #[Route('/post/create', name: 'app_admin_post_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (0 == $this->categoryRepository->count()) {
            $this->addFlash('warning', 'Il doit y avoir au moins une catégorie pour rédiger un article.');

            return $this->redirectToRoute('app_admin_post_index');
        }

        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $post->setIsPublished(false);

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', "L'article a bien été ajouté.");

            return $this->redirectToRoute('app_admin_post_index');
        }

        return $this->render('pages/admin/post/create.html.twig', [
            'postForm' => $form,
        ]);
    }

    #[Route('/post/{id<\d+>}/show', name: 'app_admin_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('pages/admin/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/post/{id<\d+>}/edit', name: 'app_admin_post_edit', methods: ['GET', 'POST'])]
    public function edit(Post $post, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $post->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();

            $this->addFlash('success', "L'article a bien été modifié.");

            return $this->redirectToRoute('app_admin_post_index');
        }

        return $this->render('pages/admin/post/edit.html.twig', [
            'postForm' => $form,
        ]);
    }

    #[Route('/post/{id<\d+>}/delete', name: 'app_admin_post_delete', methods: ['POST'])]
    public function delete(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid("delete-post-{$post->getId()}", $request->request->get('csrf_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
            $this->addFlash('success', "L'article a été supprimé avec succès.");
        }

        return $this->redirectToRoute('app_admin_post_index');
    }

    #[Route('/post/{id<\d+>}/publish', name: 'app_admin_post_publish', methods: ['POST'])]
    public function publish(Post $post, EntityManagerInterface $entityManager): Response
    {
        if (!$post->isPublished()) {
            $post->setIsPublished(true);
            $post->setPublishedAt(new \DateTimeImmutable());
            $this->addFlash('success', "L'article a été publié.");
        } else {
            $post->setIsPublished(false);
            $post->setPublishedAt(null);
            $this->addFlash('success', "L'article a été retiré de la liste.");
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_admin_post_index');
    }
}
