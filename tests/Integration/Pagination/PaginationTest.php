<?php

declare(strict_types=1);

namespace App\Tests\Integration\Pagination;

use App\Entity\Product;
use App\Pagination\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(className: Paginator::class)]
final class PaginationTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testPagination(): void
    {
        // Create a query builder to fetch data (replace with your own query logic)
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(64);

        // Create a Paginator instance
        $paginator = new Paginator($queryBuilder);

        // Paginate the first page
        $paginator->paginate(1);

        // Test pagination properties
        $this->assertSame(1, $paginator->getCurrentPage());
        // Default page size in Paginator class
        $this->assertSame(16, $paginator->getPageSize());
        $this->assertFalse($paginator->hasPreviousPage());
        $this->assertTrue($paginator->hasNextPage());
        $this->assertSame(1, $paginator->getPreviousPage());
        $this->assertSame(5, $paginator->getLastPage());
        $this->assertTrue($paginator->hasToPaginate());
        $this->assertNotNull($paginator->getResults());
        $this->assertGreaterThan(1, $paginator->getNumResults());
        $this->assertGreaterThan(1, $paginator->getNextPage());
    }
}
