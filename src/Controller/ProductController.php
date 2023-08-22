<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products/{page}', name: 'product_index')]
    public function index(Request $request, int $page, ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'paginator' => $productRepository->getPaginated($page),
        ]);
    }

    #[Route('/product/{id}', name: 'product_details')]
    public function show(Request $request, int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
