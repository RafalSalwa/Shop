<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: OrderItemRepository::class)]
#[Table(name: 'order_item')]
class OrderItem
{
    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(name: 'order_item_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;
    #[ManyToOne(targetEntity: Order::class, cascade: ["persist"], inversedBy: 'items')]
    #[JoinColumn(name: 'order_id', referencedColumnName: 'order_id')]
    private Order $order;

    #[Column(name: 'cart_item_entity', type: Types::JSON)]
    private string $cartItem;
    #[Column(name: 'cart_item_type', type: Types::STRING, length: 25)]
    private string $itemType;

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): OrderItem
    {
        $this->order = $order;
        return $this;
    }

    public function getCartItem(): string
    {
        return $this->cartItem;
    }

    public function setCartItem(string $cartItem): OrderItem
    {
        $this->cartItem = $cartItem;
        return $this;
    }

    public function getItemType(): string
    {
        return $this->itemType;
    }

    public function setItemType(string $itemType): OrderItem
    {
        $this->itemType = $itemType;
        return $this;
    }
}
