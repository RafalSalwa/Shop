<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Contracts\CartItemInterface;
use App\Enum\CartStatus;
use App\Exception\ItemNotFoundException;
use App\Repository\CartRepository;
use App\ValueObject\CouponCode;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Symfony\Component\Serializer\Annotation\Groups;
use function bcadd;
use function is_null;
use function sprintf;

#[Entity(repositoryClass: CartRepository::class)]
#[Table(name: 'cart', schema: 'interview')]
#[HasLifecycleCallbacks]
class Cart implements JsonSerializable
{
    final public const STATUS_CREATED = 'created';

    #[Id]
    #[GeneratedValue]
    #[Column(name: 'cart_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    /**
     * @var Collection<int, AbstractCartItem>
     */
    #[OneToMany(
        mappedBy: 'cart',
        targetEntity: AbstractCartItem::class,
        cascade: ['persist', 'remove'],
        fetch: 'EAGER',
        orphanRemoval: true,
    )
    ]
    #[Groups('cart')]
    private Collection $items;

    #[Column(name: 'user_id', type: Types::INTEGER)]
    #[Groups(groups: 'carts')]
    private int $userId;

    #[Column(name: 'status', type: Types::STRING, length: 25, nullable: false, enumType: CartStatus::class)]
    private CartStatus $status = CartStatus::CREATED;

    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $createdAt;

    #[Column(name: 'coupon_type', type: Types::STRING, length: 25, nullable: true)]
    private ?string $couponType = null;

    #[Column(name: 'coupon_discount', type: Types::STRING, nullable: true)]
    private ?string $couponDiscount = null;

    private ?CouponCode $coupon = null;

    #[Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    /** @throws ItemNotFoundException */
    public function addItem(CartItemInterface $newItem): void
    {
        if (false === $this->itemExists($newItem)) {
            $this->getItems()->add($newItem);
            $newItem->setCart($this);
            return;
        }

        $currentItem = $this->getItem($newItem);
        if (null === $currentItem) {
            throw new ItemNotFoundException(sprintf('Item %s not found in cart.', $newItem->getName()));
        }
        $this->removeItem($currentItem);
        $newItem->updateQuantity(
            $newItem->getQuantity() + $currentItem->getQuantity(),
        );
        $this->getItems()->add($newItem);
        $newItem->setCart($this);
    }

    public function itemExists(CartItemInterface $cartItem): bool
    {
        if (0 === $this->getItems()->count()) {
            return false;
        }

        return $this->getItems()
            ->exists(
                static fn (int $key, CartItemInterface $element): bool => $element->getReferencedEntity()->getId() === $cartItem->getReferencedEntity()->getId() &&
                    $element->getReferencedEntity()->getName() === $cartItem->getReferencedEntity()->getName()
            );
    }

    /** @return Collection<int, CartItemInterface> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getItem(CartItemInterface $newItem): ?CartItemInterface
    {
        $filtered = $this->getItems()
            ->filter(
                static fn (CartItemInterface $cartItem): bool => $cartItem->getReferencedEntity()
                    ->getId() === $newItem->getReferencedEntity()
                    ->getId()
                    && $cartItem::class === $newItem::class
            );

        return $filtered->first();
    }

    /** @throws ItemNotFoundException */
    public function removeItem(CartItemInterface $cartItem): void
    {
        if (0 === $this->getItems()->count()) {
            throw new ItemNotFoundException('Items list is empty.');
        }

        $currentItem = $this->getItem($cartItem);
        if (null === $currentItem) {
            throw new ItemNotFoundException('Item does not exists in cart');
        }
        $currentItem->setCart(null);

        $this->getItems()->removeElement($currentItem);
    }

    public function getItemById(int $id): ?CartItemInterface
    {
        $filtered = $this->getItems()
            ->filter(static fn (CartItemInterface $cartItem): bool => $cartItem->getId() === $id);

        return $filtered->first();
    }

    public function getTotalItemsCount(): int
    {
        $sum = 0;
        foreach ($this->getItems() as $item) {
            $sum += $item->getQuantity();
        }

        return $sum;
    }

    public function getItemsPrice()
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item->getTotalPrice();
        }

        return $total;
    }

    public function getTotalAmount(): int
    {
        $total = '0';
        foreach ($this->getItems() as $item) {
            $total = bcadd($total, $item->getTotalPrice());
        }
        return (int)$total;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    #[PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'status' => $this->getStatus()->value,
            'items' => $this->getItems(),
            'created_at' => $this->createdAt->getTimestamp(),
        ];
    }

    public function getStatus(): CartStatus
    {
        return $this->status;
    }

    public function setStatus(CartStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function applyCoupon(CouponCode $coupon): void
    {
        $this->couponType = $coupon->getType();
        $this->couponDiscount = $coupon->getValue();
    }

    public function getCoupon(): ?CouponCode
    {
        if (null === $this->couponType) {
            return null;
        }
        if (false === is_null($this->coupon)) {
            return $this->coupon;
        }
        $this->coupon = new CouponCode(type: $this->couponType, value: $this->couponDiscount);

        return $this->coupon;
    }
}
