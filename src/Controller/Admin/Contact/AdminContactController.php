<?php

namespace App\Controller\Admin\Contact;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class AdminContactController extends AbstractController
{
    #[Route('/contacts', name: 'app_admin_contacts', methods: ['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('pages/admin/contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/contacts/{id}', name: 'app_admin_showmess', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        return $this->render('pages/admin/contact/show-mess.html.twig', [
            'contact' => $contact,
        ]);
    }
}
