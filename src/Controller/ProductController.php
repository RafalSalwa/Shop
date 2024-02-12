<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products/{page}', name: 'products_index', defaults: ['page' => '1',], methods: ['GET'])]
    public function index(int $page, ProductRepository $productRepository): Response
    {
        return $this->render(
            'product/index.html.twig',
            ['paginator' => $productRepository->getPaginated($page)],
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
