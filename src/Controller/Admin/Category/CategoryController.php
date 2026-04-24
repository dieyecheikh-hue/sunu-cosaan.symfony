<?php

namespace App\Controller\Admin\Category;

use App\Entity\Category;
use App\Form\Admin\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/category')]
final class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_admin_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('pages/admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'app_admin_category_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCreatedAt(new \DateTimeImmutable());
            $category->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie créée avec succès.');

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('pages/admin/category/create.html.twig', [
            'categoryForm' => $form->createView(),
        ]);
    }

    #[Route('category/{id<\d+>}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();

            $this->addFlash('success', 'Catégorie modifiée avec succès.');

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('pages/admin/category/edit.html.twig', [
            'categoryForm' => $form->createView(),
        ]);
    }

    #[Route('/category/{id<\d+>}/delete', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid(
            'category-'.$category->getId(),
            $request->request->get('csrf_token')
        )) {
            $entityManager->remove($category);
        }
        $entityManager->flush();

        $this->addFlash('success', 'Catégorie supprimée avec succès.');

        return $this->redirectToRoute('app_admin_category_index');
    }
}
