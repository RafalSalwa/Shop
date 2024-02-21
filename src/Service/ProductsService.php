<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ProductRepository;

class ProductsService
{
    public function __construct(private readonly ProductRepository $productRepository)
    {}

    public function all()
    {
        return $this->productRepository->findAll();
    }
}
