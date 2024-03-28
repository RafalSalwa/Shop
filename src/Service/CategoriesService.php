<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

final readonly class CategoriesService
{
    public function __construct(private CategoryRepository $categoryRepository)
    {}

    /** @return array<array-key, Category> */
    public function list(): array
    {
        return $this->categoryRepository->findAll();
    }
}
