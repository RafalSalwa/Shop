<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\CategoryRepository;

class CategoriesService
{
    public function __construct(private readonly CategoryRepository $categoryRepository)
    {}

    public function list()
    {
        return $this->categoryRepository->findAll();
    }
}
