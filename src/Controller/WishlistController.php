<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/wishlist', name: 'wishlist_')]
final class WishlistController extends AbstractShopController
{
    #[Route(path: '/', name: 'index')]
    public function index(): Response
    {
        return $this->render('wishlist/index.html.twig');
    }
}
