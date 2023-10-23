<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\CartInsertableInterface;
use App\Entity\CartItemInterface;
use App\Exception\ItemNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class CartItemFactory
{
    public function __construct(private readonly EntityManagerInterface $entityRepository)
    {
    }

    /**
     * @throws ItemNotFoundException
     */
    public function createCartItem(string $entityType, int $id): CartItemInterface
    {
        /** @var CartInsertableInterface $entity */
        $entity = $this->entityRepository->getRepository($entityType)->find($id);
        if (!$entity) {
            throw new ItemNotFoundException();
        }

        return $entity->toCartItem();
    }
}
