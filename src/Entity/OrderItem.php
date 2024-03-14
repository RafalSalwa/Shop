<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderItemRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: OrderItemRepository::class)]
#[Table(name: 'order_item', schema: 'interview')]
class OrderItem
{
    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(name: 'order_item_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    #[Column(name: 'item_name', type: Types::STRING, nullable: false)]
    private string $name;

    #[ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    #[JoinColumn(name:'order_id', referencedColumnName: 'order_id', nullable: false)]
    private $order;

    #[Column(name: 'product_id', type: Types::INTEGER, nullable: false)]
    private int $prodId;

    #[Column(name: 'quantity', type: Types::INTEGER, nullable: false)]
    private int $quantity;

    #[Column(name: 'price', type: Types::INTEGER, nullable: false)]
    private int $price;

    #[Column(name: 'cart_item_type', type: Types::STRING, length: 25)]
    private string $itemType;

    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $createdAt;

    public function __construct(int $prodId, int $quantity, int $price, string $name)
    {
        $this->prodId = $prodId;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->name = $name;

        $this->createdAt = new DateTimeImmutable();
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    public function getItemType(): string
    {
        return $this->itemType;
    }

    public function setItemType(string $itemType): self
    {
        $this->itemType = $itemType;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }


    public function getQuantity(): int
    {
        return $this->quantity;
    }

}
