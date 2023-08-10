<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: CartItemRepository::class)]
#[Table(name: 'cart_item')]
#[HasLifecycleCallbacks]
class CartItem
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'cart_item_id', type: Types::INTEGER, unique: true, nullable: false)]
    private ?int $id = null;

    #[ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
    #[JoinColumn(name: 'cart_id', referencedColumnName: 'cart_id')]
    private Cart $cart;

    #[Column(name: 'prod_id', type: Types::INTEGER)]
    private ?int $prodId = null;
    #[Column(name: 'item_type', type: Types::STRING, length: 15)]
    private ?string $type = null;
    #[Column(name: 'quantity', type: Types::INTEGER, options: ['default' => '1'])]
    private ?int $quantity;
    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;
    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE)]
    private DateTime $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProdId(): ?int
    {
        return $this->prodId;
    }

    public function setProdId(?int $prodId): self
    {
        $this->prodId = $prodId;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function increaseQuantity(int $qty)
    {
        $this->quantity += $qty;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function prePersist(): void
    {
        $this->setCreatedAt(new DateTime('now'));
    }

    #[PreUpdate]
    public function preUpdate(): void
    {
        $this->setUpdatedAt(new DateTime('now'));
    }
}
