<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null   find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<Category> findAll()
 * @method array<Category> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Category::class);
    }
}
