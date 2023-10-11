<?php

namespace App\Entity;

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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(repositoryClass: CartRepository::class)]
#[Table(name: 'cart')]
#[HasLifecycleCallbacks]
class Cart implements JsonSerializable
{
    final public const STATUS_CREATED = 'created';
    final public const STATUS_CONFIRMED = 'confirmed';
    final public const STATUS_CANCELLED = 'cancelled';

    #[Id]
    #[GeneratedValue]
    #[Column(name: 'cart_id', type: Types::INTEGER, unique: true, nullable: false)]
    private ?int $id = null;

    #[OneToMany(
        mappedBy: 'cart',
        targetEntity: CartItem::class,
        cascade: ["persist", "remove"],
        fetch: 'EAGER',
        orphanRemoval: true
    )
    ]
    #[Groups("cart")]
    private ?Collection $items;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'carts')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    #[Groups("carts")]
    private User $user;

    #[Column(name: 'status', type: Types::STRING, length: 25, nullable: false)]
    private string $status = self::STATUS_CREATED;

    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime $updatedAt;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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

    public function addItem(CartItemInterface $item): self
    {
        if (!$this->itemExists($item)) {
            $item->setCart($this);
            $this->getItems()->add($item);
        } else {
            /** @var CartItem $existingItem */
            $existingItem = $this->getFilteredItems($item)->first();
            $existingItem->increaseQuantity();
        }

        return $this;
    }

    public function itemExists(CartItemInterface $cartItem): bool
    {
        /** @var CartItemInterface $element */
        return $this->getItems()->exists(
            fn($key, $element) => $element->getReferenceEntity()->getId() === $cartItem->getReferenceEntity()->getId(
                ) &&
                $element::class === $cartItem::class
        );
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilteredItems(CartItem $newItem): ReadableCollection
    {
        return $this->getItems()->filter(
            fn(CartItem $cartItem) => $cartItem->getReferenceEntity()->getId() === $newItem->getReferenceEntity(
                )->getId() &&
                $cartItem::class === $newItem::class
        );
    }

    public function removeItem(CartItem $item)
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);

            $item->setCart(null);
        }
    }

    public function itemTypeExists(CartItemInterface $cartItem): bool
    {
        /* @var CartItem $element */
        return $this->getItems()->exists(fn($key, $element) => $element::class === $cartItem::class);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
