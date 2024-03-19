<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Contracts\StockManageableInterface;
use App\Entity\Product;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Product::class);
    }

    public function getPaginated(int $page): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('p', 's')
            ->innerJoin('p.subscriptionPlan', 's')
            ->where('p.unitsInStock > 0')
            ->orderBy('s.tier', 'ASC')
            ->addOrderBy('p.unitsInStock', 'DESC');

        return (new Paginator($queryBuilder))->paginate($page);
    }

    public function updateStock(StockManageableInterface $entity, int $qty): void
    {
        $this
            ->createQueryBuilder('p')
            ->update($this->getEntityName(), 'p')
            ->set('p.unitsInStock', $qty)
            ->where('p.id = :id')
            ->setParameter('id', $entity->getId())
            ->getQuery()
            ->execute();
    }
}
