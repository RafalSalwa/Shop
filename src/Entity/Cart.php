<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\User;
use App\Repository\CartRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Symfony\Component\Serializer\Annotation\Groups;

use function assert;

#[Entity(repositoryClass: CartRepository::class)]
#[Table(name: 'cart', schema: 'interview')]
#[HasLifecycleCallbacks]
class Cart implements JsonSerializable
{
    final public const STATUS_CREATED = 'created';

    final public const STATUS_CONFIRMED = 'confirmed';

    final public const STATUS_CANCELLED = 'cancelled';

    #[Id]
    #[GeneratedValue]
    #[Column(name: 'cart_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    /** @var Collection<int, CartItem> */
    #[OneToMany(
        mappedBy: 'cart',
        targetEntity: CartItem::class,
        cascade: ['persist', 'remove'],
        fetch: 'EAGER',
        orphanRemoval: true,
    )
    ]
    #[Groups('cart')]
    private Collection $items;

    #[Column(name: 'user_id', type: Types::INTEGER, nullable: false)]
    #[Groups('carts')]
    private int $userId;

    #[Column(name: 'status', type: Types::STRING, length: 25, nullable: false)]
    private string $status = self::STATUS_CREATED;

    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime|null $updatedAt = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getUpdatedAt(): DateTime|null
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function addItem(CartItemInterface $cartItem): self
    {
        if (! $this->itemExists($cartItem)) {
            $cartItem->setCart($this);
            $this->getItems()
                ->add($cartItem);

            return $this;
        }

        $existingItem = $this->getFilteredItems($cartItem)
            ->first();
        assert($existingItem instanceof CartItem);
        $existingItem->increaseQuantity();

        return $this;
    }

    public function itemExists(CartItemInterface $cartItem): bool
    {
        return $this->getItems()
            ->exists(
                /** @var CartItemInterface $element */
                static fn ($key, CartItemInterface $element): bool => $element->getReferenceEntity()
                    ->getId() === $cartItem->getReferenceEntity()
                    ->getId()
                    && $element::class === $cartItem::class
            );
    }

    /** @return Collection<int, CartItem>|null */
    public function getItems(): Collection|null
    {
        return $this->items;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    /** @return ReadableCollection<int,CartItem> */
    public function getFilteredItems(CartItemInterface $newItem): ReadableCollection
    {
        return $this->getItems()
            ->filter(
                static fn (CartItemInterface $cartItem): bool => $cartItem->getReferenceEntity()
                    ->getId() === $newItem->getReferenceEntity()
                    ->getId()
                    && $cartItem::class === $newItem::class
            );
    }

    public function removeItem(CartItem $cartItem): void
    {
        if (! $this->items->contains($cartItem)) {
            return;
        }

        $this->items->removeElement($cartItem);

        $cartItem->setCart(null);
    }

    public function itemTypeExists(CartItemInterface $cartItem): bool
    {
        return $this->getItems()
            ->exists(static fn ($key, $element): bool => $element::class === $cartItem::class);
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function setUser(User $user): self
    {
        $this->setUserId($user->getId());

        return $this;
    }

    #[PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    #[PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTime('now');
    }

    public function jsonSerialize(): mixed
    {
        return [
            'status' => $this->getStatus(),
            'items' => $this->getItems(),
            'created_at' => $this->getCreatedAt(),
        ];
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
