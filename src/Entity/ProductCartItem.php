<?php

namespace App\Entity;

use App\Repository\ProductCartItemRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: ProductCartItemRepository::class)]
class ProductCartItem extends CartItem
{
    #[ManyToOne(targetEntity: Product::class)]
    #[JoinColumn(referencedColumnName: 'product_id')]
    private $destinationEntity;

    public function getType(): ?string
    {
        return 'product';
    }

    public function getDestinationEntity()
    {
        return $this->destinationEntity;
    }

    public function setDestinationEntity(CartInsertableInterface $product)
    {
        $this->destinationEntity = $product;
    }
}
