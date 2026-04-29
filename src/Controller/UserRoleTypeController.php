<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserRoleTypeController extends AbstractController
{
    #[Route('/user/role/type', name: 'app_user_role_type')]
    public function index(): Response
    {
        return $this->render('user_role_type/index.html.twig', [
            'controller_name' => 'UserRoleTypeController',
        ]);
    }
}
