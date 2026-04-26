<?php

namespace App\Controller\User\Suggestion;

use App\Entity\Suggestion;
use App\Form\SuggestionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class SuggestionController extends AbstractController
{
    #[Route('/suggestion', name: 'app_user_suggestion_index', methods: ['GET', 'POST'])]
    public function index(EntityManagerInterface $entityManager, Request $request, MailerInterface $mailer): Response
    {
        $suggestion = new Suggestion();
        $form = $this->createForm(SuggestionFormType::class, $suggestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $suggestion->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($suggestion);
            $entityManager->flush();

            $email = (new Email())
            ->from('cybertarantula9@gmail.com')
            ->to('cybertarantula9@gmail.com')
            ->subject('Nouvelle suggestion de '.$suggestion->getFirstName())
            ->text('Suggestion : '.$suggestion->getContent());

            $mailer->send($email);

            $this->addFlash('success', 'Votre suggestion a bien été envoyée. Merci bien.');

            return $this->redirectToRoute('app_user_suggestion_index');
        }

        return $this->render('pages/user/suggestion/index.html.twig', [
            'suggestionForm' => $form,
        ]);
    }
}
