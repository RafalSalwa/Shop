<?php

declare(strict_types=1);

namespace App\Controller\Shop;

use App\Service\ProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/shop', name: 'shop_')]
class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductsService $service): Response
    {
        return $this->render(
            'shop/index/index.html.twig',
            [
                'products' => $service->all(),
            ]
        );
    }
}
