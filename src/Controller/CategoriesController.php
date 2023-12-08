<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CategoriesService;
use App\Service\ProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/categories/{slug}', name: 'categories_index', defaults: ['_format' => 'html'], methods: ['GET'])]
    public function index(ProductsService $service): Response
    {
        return $this->render(
            'categories/index.html.twig',
            [
                'categories' => $service->all(),
            ],
        );
    }

    public function list(CategoriesService $service): Response
    {
        return $this->render(
            'partials/categories_list.html.twig',
            [
                'categories' => $service->list(),
            ],
        );
    }
}
