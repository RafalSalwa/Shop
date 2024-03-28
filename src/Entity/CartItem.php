<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Contracts\CartInsertableInterface;
use App\Entity\Contracts\CartItemInterface;
use App\Enum\CartItemTypeEnum;
use App\Repository\CartItemRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

use function bcmul;

#[Entity(repositoryClass: CartItemRepository::class)]
#[Table(name: 'cart_item', schema: 'interview')]
#[HasLifecycleCallbacks]
class CartItem implements CartItemInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'cart_item_id', type: Types::INTEGER, unique: true, nullable: false)]
    protected int $id;

    #[ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
    #[JoinColumn(name: 'cart_id', referencedColumnName: 'cart_id')]
    #[Groups(groups: 'cart_item')]
    protected ?Cart $cart = null;

    #[Column(name: 'quantity', type: Types::INTEGER, nullable: false, options: ['default' => '1'])]
    protected int $quantity;

    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected readonly DateTimeImmutable $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $updatedAt = null;

    #[ManyToOne(targetEntity: CartInsertableInterface::class, fetch: 'EAGER')]
    #[JoinColumn(referencedColumnName: 'product_id', nullable: false)]
    private CartInsertableInterface $referencedEntity;

    public function __construct(CartInsertableInterface $referencedEntity, int $quantity)
    {
        $this->referencedEntity = $referencedEntity;
        $this->quantity = $quantity;

        $this->createdAt = new DateTimeImmutable();
    }

    final public function setCart(?Cart $cart): void
    {
        $this->cart = $cart;
    }

    final public function getName(): string
    {
        return $this->referencedEntity->getName();
    }

    final public function getType(): string
    {
        return CartItemTypeEnum::from(ClassUtils::getClass($this->getReferencedEntity()))->value;
    }

    final public function getReferencedEntity(): CartInsertableInterface
    {
        return $this->referencedEntity;
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
