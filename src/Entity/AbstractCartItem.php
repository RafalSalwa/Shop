<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Contracts\CartItemInterface;
use App\Enum\CartItemTypeEnum;
use App\Repository\CartItemRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Util\ClassUtils;
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
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

use function bcmul;

/** @psalm-suppress PropertyNotSetInConstructor */
#[Entity(repositoryClass: CartItemRepository::class)]
#[Table(name: 'cart_item', schema: 'interview')]
#[HasLifecycleCallbacks]
#[InheritanceType(value: 'SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'item_type', type: Types::STRING, length: 30)]
#[DiscriminatorMap(value: ['product' => ProductCartItem::class])]
abstract class AbstractCartItem implements CartItemInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'cart_item_id', type: Types::INTEGER, unique: true, nullable: false)]
    protected int $id = 0;

    #[ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
    #[JoinColumn(name: 'cart_id', referencedColumnName: 'cart_id', nullable: false)]
    #[Groups(groups: 'cart_item')]
    protected Cart $cart;

    #[Column(name: 'quantity', type: Types::INTEGER, nullable: false, options: ['default' => '1'])]
    protected int $quantity = 1;

    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected readonly DateTimeImmutable $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $updatedAt = null;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;

        $this->createdAt = new DateTimeImmutable();
    }

    final public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }

    final public function getType(): string
    {
        return CartItemTypeEnum::from(ClassUtils::getClass($this->getReferencedEntity()))->value;
    }

    final public function getId(): int
    {
        return $this->id;
    }

    final public function getTotalPrice(): int
    {
        return (int)bcmul((string)$this->getQuantity(), (string)$this->getPrice(), 0);
    }

    final public function getQuantity(): int
    {
        return $this->quantity;
    }

    final public function getPrice(): int
    {
        return $this->getReferencedEntity()->getPrice();
    }

    final public function updateQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        $this->updatedAt = new DateTimeImmutable();
    }

    final public function getItemType(): string
    {
        return self::class;
    }

    final public function getCart(): Cart
    {
        return $this->cart;
    }
}
