<?php

namespace App\Entity;

use App\Repository\ProductCartItemRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use JsonSerializable;

#[Entity(repositoryClass: ProductCartItemRepository::class)]
class ProductCartItem extends CartItem implements JsonSerializable, CartItemInterface
{
    #[ManyToOne(targetEntity: Product::class)]
    #[JoinColumn(referencedColumnName: 'product_id')]
    private CartInsertableInterface $referenceEntity;

    public function getType(): string
    {
        return 'product';
    }

    public function getItemName(): string
    {
        return $this->referenceEntity->getName();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->getReferenceEntity()->getName(),
            'category' => $this->getReferenceEntity()->getCategory()->getName(),
            'type' => $this->getTypeName(),
            'quantity' => $this->getQuantity(),
        ];
    }

    public function getReferenceEntity(): CartInsertableInterface
    {
        return $this->referenceEntity;
    }

    public function setReferenceEntity(CartInsertableInterface $product): void
    {
        $this->referenceEntity = $product;
    }

    public function getTypeName(): string
    {
        return 'product';
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getDisplayName(): string
    {
        return sprintf(
            "%s (%s)",
            $this->getReferencedEntity()->getName(),
            $this->getReferencedEntity()->getCategory()->getName()
        );
    }

    public function getReferencedEntity(): CartInsertableInterface
    {
        return $this->referenceEntity;
    }

    public function getPrice(): float
    {
        // TODO: Implement getPrice() method.
    }

    public function getTotalPrice(): float
    {
        // TODO: Implement getTotalPrice() method.
    }

    public function setReferencedEntity(CartInsertableInterface $entity): CartItemInterface
    {
        $this->referenceEntity = $entity;

        return $this;
    }

    public function toCartItem(): CartItem
    {
        // TODO: Implement toCartItem() method.
    }

    public function setQuantity(int $quantity): CartItemInterface
    {
        $this->quantity = $quantity;
        return $this;
    }
}
