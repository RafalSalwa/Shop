<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[asController]
#[Route(path: '/user', name: 'user_', methods: ['GET'])]
final class ProfileController extends AbstractShopController
{
    #[Route(path: '/profile', name: 'profile')]
    public function index(): Response
    {
        return $this->render('user/profile.html.twig', []);
    }
}
