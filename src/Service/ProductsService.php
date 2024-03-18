<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Pagination\Paginator;
use App\Repository\ProductRepository;

final class ProductsService
{
    public function __construct(private readonly ProductRepository $productRepository)
    {}

    /** @return array<Product>|null */
    public function all(): ?array
    {
        return $this->productRepository->findAll();
    }

    public function byId(int $prodId): ?Product
    {
        return $this->productRepository->find($prodId);
    }

    public function find(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    public function getPaginated(int $page): Paginator
    {
        return $this->productRepository->getPaginated($page);
    }
}
