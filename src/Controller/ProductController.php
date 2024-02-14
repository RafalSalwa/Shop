<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Security\CartAddVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @see CartAddVoter
 */
class ProductController extends AbstractController
{
    #[Route('/products/{page}', name: 'products_index', defaults: ['page' => '1', 'sort' => 'default'], methods: ['GET'])]
    public function index(int $page, string $sort, ProductRepository $productRepository): Response
    {
        return $this->render(
            'product/index.html.twig',
            ['paginator' => $productRepository->getPaginated($page, $sort)],
        );
    }

    #[Route('/product/{id}', name: 'products_details')]
    public function details(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        return $this->render(
            'product/details.html.twig',
            ['product' => $product],
        );
    }
}
