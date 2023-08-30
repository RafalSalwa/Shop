<?php

namespace App\Entity;

use App\Repository\ProductCartItemRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use JsonSerializable;

#[Entity(repositoryClass: ProductCartItemRepository::class)]
class ProductCartItem extends CartItem implements JsonSerializable
{
    #[ManyToOne(targetEntity: Product::class)]
    #[JoinColumn(referencedColumnName: 'product_id')]
    private CartInsertableInterface $destinationEntity;

    public function getType(): ?string
    {
        return 'product';
    }

    public function getItemName(): string
    {
        return $this->destinationEntity->getName();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->getDestinationEntity()->getName(),
            'category' => $this->getDestinationEntity()->getCategory()->getName(),
            'type' => $this->getTypeName(),
            'quantity' => $this->getQuantity(),
        ];
    }

    public function getDestinationEntity(): CartInsertableInterface
    {
        return $this->destinationEntity;
    }

    public function setDestinationEntity(CartInsertableInterface $product): void
    {
        $this->destinationEntity = $product;
    }

    public function getTypeName(): string
    {
        return 'product';
    }
}
