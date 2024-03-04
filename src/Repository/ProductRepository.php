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
        ;

        return (new Paginator($queryBuilder))->paginate($page);
    }

    public function decreaseQty(StockManageableInterface $stockManageable, int $qty): void
    {
        $this
            ->createQueryBuilder('p')
            ->update($this->getEntityName(), 'p')
            ->set('p.unitsInStock', $stockManageable->getUnitsInStock() - $qty)
            ->where('p.id = :id')
            ->setParameter('id', $stockManageable->getId())
            ->getQuery()
            ->execute()
        ;
    }

    public function increaseQty(StockManageableInterface $stockManageable, int $qty): void
    {
        $this
            ->createQueryBuilder('p')
            ->update($this->getEntityName(), 'p')
            ->set('p.unitsInStock', $stockManageable->getUnitsInStock() + $qty)
            ->where('p.id = :id')
            ->setParameter('id', $stockManageable->getId())
            ->getQuery()
            ->execute()
        ;
    }
}
