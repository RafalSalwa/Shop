<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Contracts\CartInsertableInterface;
use App\Entity\Contracts\CartItemInterface;
use App\Repository\ProductCartItemRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use JsonSerializable;
use function sprintf;

#[Entity(repositoryClass: ProductCartItemRepository::class)]
class ProductCartItem extends CartItem implements JsonSerializable, CartItemInterface
{
    #[ManyToOne(targetEntity: Product::class)]
    #[JoinColumn(referencedColumnName: 'product_id')]
    protected CartInsertableInterface $referenceEntity;

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
            'name' => $this->getReferenceEntity()
                ->getName(),
            'category' => $this->getReferenceEntity()
                ->getCategory()
                ->getName(),
            'type' => $this->getTypeName(),
            'quantity' => $this->getQuantity(),
        ];
    }

    public function getReferenceEntity(): CartInsertableInterface
    {
        return $this->referenceEntity;
    }

    public function setReferenceEntity(CartInsertableInterface $cartInsertable): void
    {
        $this->referenceEntity = $cartInsertable;
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
            '%s (%s)',
            $this->getReferencedEntity()
                ->getName(),
            $this->getReferencedEntity()
                ->getCategory()
                ->getName(),
        );
    }

    public function getReferencedEntity(): CartInsertableInterface
    {
        return $this->referenceEntity;
    }

    public function getPrice(): float
    {
    }

    public function getTotalPrice(): float
    {
    }

    public function setReferencedEntity(CartInsertableInterface $cartInsertable): CartItemInterface
    {
        $this->referenceEntity = $cartInsertable;

        return $this;
    }

    public function toCartItem(): CartItem
    {
    }

    public function setQuantity(int $quantity): CartItemInterface
    {
        $this->quantity = $quantity;

        return $this;
    }
}
