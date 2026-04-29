<?php

namespace App\Controller\Visitor\Blog;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_visitor_blog_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy(['isPublished' => true]);

        return $this->render('pages/visitor/blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/blog/{slug}', name: 'app_visitor_blog_show', methods: ['GET', 'POST'])]
    public function show(string $slug, PostRepository $postRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = $postRepository->findOneBy(['slug' => $slug, 'isPublished' => true]);

        if (!$post) {
            throw $this->createNotFoundException('Article introuvable.');
        }

        $commentForm = null;

        if ($this->getUser()) {
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setPost($post);
                $comment->setUser($this->getUser());
                $comment->setCreatedAt(new \DateTimeImmutable());
                $entityManager->persist($comment);
                $entityManager->flush();
                $this->addFlash('success', 'Commentaire ajouté !');

                return $this->redirectToRoute('app_visitor_blog_show', ['slug' => $post->getSlug()]);
            }

            $commentForm = $form->createView();
        }

        return $this->render('pages/visitor/blog/show_visitor.html.twig', [
            'post' => $post,
            'commentForm' => $commentForm,
        ]);
    }
}
