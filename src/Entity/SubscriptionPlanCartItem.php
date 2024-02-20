<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SubscriptionPlanCartItemRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: SubscriptionPlanCartItemRepository::class)]
class SubscriptionPlanCartItem extends CartItem implements CartItemInterface
{
    #[ManyToOne(targetEntity: SubscriptionPlan::class)]
    #[JoinColumn(referencedColumnName: 'plan_id')]
    protected CartInsertableInterface $referenceEntity;

    public function getType(): string
    {
        return 'plan';
    }

    public function getTypeName(): string
    {
        return 'plan';
    }

    public function getReferenceEntity(): CartInsertableInterface
    {
        return $this->referenceEntity;
    }

    public function getItemName(): string
    {
        return $this->referenceEntity->getName();
    }

    public function getReferencedEntity(): CartInsertableInterface
    {
        return $this->referenceEntity;
    }

    public function setReferencedEntity(CartInsertableInterface $entity): CartItemInterface
    {
        $this->referenceEntity = $entity;

        return $this;
    }

    public function toCartItem(): CartItem
    {
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): CartItemInterface
    {
        $this->quantity = $quantity;

        return $this;
    }
}
