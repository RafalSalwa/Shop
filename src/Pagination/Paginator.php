<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Pagination;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Exception;
use Traversable;

use function ceil;
use function count;
use function max;
use function min;

/** @psalm-api */
final class Paginator
{
    private int $currentPage = 1;

    private int $numResults = 0;

    private int $pageSize = 16;

    /** @var Traversable<mixed, mixed>|null */
    private ?Traversable $traversable = null;

    public function __construct(
        private readonly DoctrineQueryBuilder $doctrineQueryBuilder,
    ) {}

    /** @throws Exception */
    public function paginate(int $page = 1): self
    {
        $this->currentPage = max(1, $page);
        $firstResult = ($this->currentPage - 1) * $this->pageSize;

        $query = $this->doctrineQueryBuilder
            ->setFirstResult($firstResult)
            ->setMaxResults($this->pageSize)
            ->getQuery()
        ;

        /** @var array<string, mixed> $joinDqlParts */
        $joinDqlParts = $this->doctrineQueryBuilder->getDQLPart('join');

        if ([] === $joinDqlParts) {
            $query->setHint(CountWalker::HINT_DISTINCT, false);
        }

        $paginator = new DoctrinePaginator($query, true);

        /** @var array<string, mixed>|null $havingDqlParts */
        $havingDqlParts = $this->doctrineQueryBuilder->getDQLPart('having');

        if (null !== $havingDqlParts && count($havingDqlParts) > 0) {
            $paginator->setUseOutputWalkers(true);
        }

        $this->traversable = $paginator->getIterator();
        $this->numResults = $paginator->count();

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    /** @psalm-return int<1, max> */
    public function getPreviousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->getLastPage();
    }

    public function getLastPage(): int
    {
        return (int)ceil($this->numResults / $this->pageSize);
    }

    public function getNextPage(): int
    {
        return min($this->getLastPage(), $this->currentPage + 1);
    }

    public function hasToPaginate(): bool
    {
        return $this->numResults > $this->pageSize;
    }

    public function getNumResults(): int
    {
        return $this->numResults;
    }

    /** @return Traversable<int, object>|null */
    public function getResults(): ?Traversable
    {
        return $this->traversable;
    }
}
