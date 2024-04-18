<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoriesService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(className: CategoriesService::class)]
#[UsesClass(className: CategoryRepository::class)]
#[UsesClass(className: Category::class)]
final class CategoriesServiceTest extends WebTestCase
{
    private CategoryRepository $categoryRepository;

    private CategoriesService $categoriesService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock CategoryRepository
        $this->categoryRepository = $this->createMock(CategoryRepository::class);

        // Create an instance of CategoriesService with the mock repository
        $this->categoriesService = new CategoriesService($this->categoryRepository);
    }

    public function testListReturnsCategories(): void
    {
        // Create mock categories data
        $categories = [
            new Category('Category 1'),
            new Category('Category 2'),
            new Category('Category 3'),
        ];

        // Configure the mock categoryRepository to return the mock categories
        $this->categoryRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($categories);

        // Call the list() method of CategoriesService
        $result = $this->categoriesService->list();

        // Assert that the result matches the expected categories
        $this->assertSame($categories, $result);
    }
}
