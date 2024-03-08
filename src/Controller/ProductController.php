<?php

declare(strict_types=1);

namespace App\Controller;

use App\Security\Voter\AddToCartVoter;
use App\Service\ProductsService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/** @see AddToCartVoter */
#[asController]
#[Route(path: '/products', name: 'products_', methods: ['GET', 'POST'])]
final class ProductController extends AbstractShopController
{
    #[Route(
        path: '/{page}',
        name: 'index',
        defaults: ['page' => '1', 'sort' => 'default'],
        methods: ['GET'],
    )]
    public function index(int $page, ProductsService $productsService): Response
    {
        return $this->render(
            'product/index.html.twig',
            ['paginator' => $productsService->getPaginated($page)],
        );
    }

    #[Route(path: '/product/{id}', name: 'details')]
    public function details(int $id, ProductsService $productsService): Response
    {
        return $this->render(
            'product/details.html.twig',
            ['product' => $productsService->find($id)],
        );
    }
}
