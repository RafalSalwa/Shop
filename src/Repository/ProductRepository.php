<?php

namespace App\Repository;

use App\Entity\Product;
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
            ->orderBy('p.id', 'DESC');

        return (new Paginator($qb))->paginate($page);
    }
}
