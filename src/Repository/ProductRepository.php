<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\StockManageableInterface;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getPaginated(int $page)
    {
        $qb = $this->createQueryBuilder('p')
            ->select("p", "s")
            ->innerJoin("p.requiredSubscription", "s")
            ->where("p.unitsInStock > 0")
            ->orderBy('p.id', 'DESC');

        return (new Paginator($qb))->paginate($page);
    }

    public function decreaseQty(StockManageableInterface $product, int $qty)
    {
        return $this
            ->createQueryBuilder('p')
            ->update($this->getEntityName(), 'p')
            ->set('p.unitsInStock', $product->getUnitsInStock() - $qty)
            ->where('p.id = :id')
            ->setParameter('id', $product->getId())
            ->getQuery()
            ->execute();
    }

    public function increaseQty(Product $product, $qty)
    {
        return $this
            ->createQueryBuilder('p')
            ->update($this->getEntityName(), 'p')
            ->set('p.unitsInStock', $product->getUnitsInStock() + $qty)
            ->where('p.id = :id')
            ->setParameter('id', $product->getId())
            ->getQuery()
            ->execute();
    }

}
