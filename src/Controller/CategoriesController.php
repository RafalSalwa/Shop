<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CategoriesService;
use App\Service\ProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[asController]
#[Route(path: '/categories', name: 'categories_', methods: ['GET'])]
final class CategoriesController extends AbstractController
{
    #[Route(path: '/{slug}', name: 'index', defaults: ['_format' => 'html'], methods: ['GET'])]
    public function index(ProductsService $service): Response
    {
        return $this->render(
            'categories/index.html.twig',
            [
                'categories' => $service->all(),
            ],
        );
    }

    public function list(CategoriesService $categoriesService): Response
    {
        return $this->render(
            'partials/categories_list.html.twig',
            [
                'categories' => $categoriesService->list(),
            ],
        );
    }
}
