<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($contact);
            $entityManager->flush();

            $email = (new Email())
                ->from($contact->getEmail())
                ->to('cybertarantula9@gmail.com')
                ->subject('Nouveau message de contact')
                ->html('
                    <p><strong>Prénom :</strong> '.$contact->getFirstName().'</p>
                    <p><strong>Nom :</strong> '.$contact->getLastName().'</p>
                    <p><strong>Email :</strong> '.$contact->getEmail().'</p>
                    <p><strong>Message :</strong> '.nl2br($contact->getMessage()).'</p>
                ');

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('pages/visitor/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
