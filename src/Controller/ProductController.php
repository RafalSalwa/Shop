<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products/{page}', name: 'product_index')]
    public function index(int $page, ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'paginator' => $productRepository->getPaginated($page),
        ]);
    }

    #[Route('/products/{id}', name: 'product_details')]
    public function details(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        return $this->render('product/details.html.twig', [
            'product' => $product,
        ]);
    }
}
