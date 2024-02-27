<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Security\Voter\CartAddVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use function dd;

/** @see CartAddVoter */
#[asController]
#[Route(path: '/products', name: 'products_', methods: ['GET', 'POST'])]
final class ProductController extends AbstractController
{
    #[Route(
        path: '/{page}',
        name: 'index',
        defaults: ['page' => '1', 'sort' => 'default'],
        methods: ['GET'],
    )]
    public function index(int $page, string $sort, ProductRepository $productRepository): Response
    {
        return $this->render(
            'product/index.html.twig',
            ['paginator' => $productRepository->getPaginated($page, $sort)],
        );
    }

    #[Route(path: '/product/{id}', name: 'details')]
    public function details(int $id, ProductRepository $productRepository): Response
    {
        dd($this->getUser());
        $product = $productRepository->find($id);

        return $this->render(
            'product/details.html.twig',
            ['product' => $product],
        );
    }
}
