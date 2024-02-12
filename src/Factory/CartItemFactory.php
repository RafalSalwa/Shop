<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\CartInsertableInterface;
use App\Entity\CartItemInterface;
use App\Entity\Product;
use App\Entity\SubscriptionPlan;
use App\Exception\ItemNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Mockery\Exception;
use Psr\Log\LoggerInterface;

class CartItemFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    public function create(string $entityType, int $id): CartItemInterface
    {
        try {
            if (is_string($entityType)) {
                $entityTypeMap = [
                    'product' => Product::class,
                    'plan' => SubscriptionPlan::class,
                ];
                $entityType = $entityTypeMap[$entityType];
            }

            /** @var CartInsertableInterface $entity */
            $entity = $this->entityRepository->getRepository($entityType)
                ->find($id);
            if (null === $entity) {
                throw new ItemNotFoundException();
            }

            return $entity->toCartItem();
        }catch(\Exception $e){
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
