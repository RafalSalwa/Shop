<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Contracts\CartInsertableInterface;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Contracts\StockManageableInterface;
use App\Enum\CartItemTypeEnum;
use App\Repository\CartItemRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
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

#[Entity(repositoryClass: CartItemRepository::class)]
#[Table(name: 'cart_item', schema: 'interview')]
#[HasLifecycleCallbacks]
#[InheritanceType(value: 'SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'item_type', type: Types::STRING, length: 30, enumType: CartItemTypeEnum::class)]
abstract class AbstractCartItem implements CartItemInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'cart_item_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    #[ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
    #[JoinColumn(name: 'cart_id', referencedColumnName: 'cart_id')]
    #[Groups(groups: 'cart_item')]
    private Cart|null $cart = null;

    #[Column(name: 'quantity', type: Types::INTEGER, nullable: false, options: ['default' => '1'])]
    private int $quantity;

    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private readonly DateTimeImmutable $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    private readonly CartItemTypeEnum $itemType;

    public function __construct(protected CartInsertableInterface $referencedEntity, int $quantity)
    {
        $this->itemType = CartItemTypeEnum::from($referencedEntity::class);
        $this->createdAt = new DateTimeImmutable();

        $this->quantity = $quantity;
    }

    final public function setCart(Cart|null $cart): void
    {
        $this->cart = $cart;
    }

    final public function getName(): string
    {
        return $this->referencedEntity->getName();
    }

    public function getTypeName(): string
    {
        return 'cart_item';
    }

    final public function getType(): string
    {
        return CartItemTypeEnum::from(ClassUtils::getClass($this->getReferencedEntity()))->value;
    }

    final public function getReferencedEntity(): CartInsertableInterface|StockManageableInterface
    {
        return $this->referencedEntity;
    }

    final public function getId(): int
    {
        return $this->id;
    }

    final public function getTotalPrice(): string
    {
        return bcmul((string) $this->getQuantity(), $this->getPrice(), 2);
    }

    final public function getQuantity(): int
    {
        return $this->quantity;
    }

    final public function getPrice(): string
    {
        return (string) $this->getReferencedEntity()->getPrice();
    }

    public function updateQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        $this->updatedAt = new DateTimeImmutable();
    }

    final public function getItemType(): string
    {
        return self::class;
    }
}
