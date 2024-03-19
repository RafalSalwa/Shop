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
use function is_null;
use function max;
use function min;

final class Paginator
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them under parameters section in config/services.yaml file.
     *
     * See https://symfony.com/doc/current/best_practices.html#use-constants-to-define-options-that-rarely-change
     */
    public const PAGE_SIZE = 15;

    private int $currentPage;

    private int $numResults;

    /** @var Traversable<int, object> */
    private Traversable $traversable;

    public function __construct(
        private readonly DoctrineQueryBuilder $doctrineQueryBuilder,
        private readonly int $pageSize = self::PAGE_SIZE,
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

        /** @var array<string, mixed> $havingDqlParts */
        $havingDqlParts = $this->doctrineQueryBuilder->getDQLPart('having');

        if (false === is_null($havingDqlParts) && count($havingDqlParts) > 0) {
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

    /** @return Traversable<int, object> */
    public function getResults(): Traversable
    {
        return $this->traversable;
    }
}
