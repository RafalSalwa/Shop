<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CartItemRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PrePersist;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(repositoryClass: CartItemRepository::class)]
#[HasLifecycleCallbacks]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'item_type', type: Types::STRING, length: 30)]
#[DiscriminatorMap(['product' => ProductCartItem::class, 'subscription_plan' => SubscriptionPlanCartItem::class])]
class CartItem implements SerializerInterface, CartItemInterface
{
    #[Column(name: 'quantity', type: Types::INTEGER, nullable: false, options: ['default' => '1'])]
    protected int $quantity;

    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected DateTimeInterface $createdAt;
    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTime $updatedAt = null;

    #[ManyToOne(targetEntity: Cart::class)]
    #[JoinColumn(name: 'cart_id', referencedColumnName: 'cart_id')]
    #[Groups('cart_item')]
    protected ?Cart $cart = null;
    /**
     * @var CartInsertableInterface
     */
    protected CartInsertableInterface $referenceEntity;
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'cart_item_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    public function getId(): int
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

    public function getName(): string
    {
        return 'cart_item';
    }

    public function increaseQuantity(int $qty = 1): void
    {
        $this->quantity += $qty;
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

    #[PrePersist]
    public function prePersist(): void
    {
        $this->setCreatedAt(new DateTime('now'));
        $this->setCreatedAt(new DateTime('now'));
    }

    public function setUser(UserInterface $getUser): void
    {
    }

    /**
     * @return (int|mixed)[]
     *
     * @psalm-return array{id: int, username: mixed, verified: mixed, active: mixed}
     */
    public function serialize(
        $data,
        string $format,
        SerializationContext $context = null,
        string $type = null
    ): string {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'verified' => $this->verified,
            'active' => $this->active,
        ];
    }

    /**
     * @return void
     */
    public function deserialize(string $data, string $type, string $format, DeserializationContext $context = null)
    {
        // TODO: Implement deserialize() method.
    }

    public function getDisplayName(): string
    {
        return 'cart_item';
    }

    public function getTypeName(): string
    {
        return 'cart_item';
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getType(): string
    {
        // TODO: Implement getType() method.
    }

    public function getPrice(): float
    {
        // TODO: Implement getPrice() method.
    }

    public function getTotalPrice(): float
    {
        // TODO: Implement getTotalPrice() method.
    }

    public function getReferencedEntity(): CartInsertableInterface
    {
        // TODO: Implement getReferencedEntity() method.
    }

    public function setReferencedEntity(CartInsertableInterface $entity): CartItemInterface
    {
        $this->referenceEntity = $entity;

        return $this;
    }

    public function toCartItem(): self
    {
        // TODO: Implement toCartItem() method.
    }

    public function getQuantity(): int
    {
        // TODO: Implement getQuantity() method.
    }

    public function setQuantity(int $quantity): CartItemInterface
    {
        // TODO: Implement setQuantity() method.
    }

    public function getReferenceEntity(): CartInsertableInterface
    {
        // TODO: Implement getReferenceEntity() method.
    }
}
