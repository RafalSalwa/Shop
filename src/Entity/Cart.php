<?php

namespace App\Entity;

use App\Repository\CartRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\UserInterface;

#[Entity(repositoryClass: CartRepository::class)]
#[Table(name: 'cart')]
#[HasLifecycleCallbacks]
class Cart
{
    public const STATUS_CREATED = 'created';
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'cart_id', type: Types::INTEGER, unique: true, nullable: false)]
    private ?int $id = null;

    #[OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, cascade: ['all'], fetch: 'EAGER', orphanRemoval: true)]
    private Collection $items;

    private ?string $cartItem = null;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'carts')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    private UserInterface $user;
    #[Column(name: 'status', type: Types::STRING, length: 25)]
    private ?string $status = null;
    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;
    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime $updatedAt;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getCartItem(): ?string
    {
        return $this->cartItem;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
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

    public function addItem(CartItem $item): self
    {
        if (!$this->itemExists($item)) {
            $item->setCart($this);
            $this->getItems()->add($item);
        } else {
            /* @var CartItem $existingItem */
            $existingItem = $this->getFilteredItems($item)->first();
            $existingItem->increaseQuantity();
        }

        return $this;
    }

    public function itemExists(CartItem $cartItem): bool
    {
        /* @var CartItem $element */
        return $this->getItems()->exists(function ($key, $element) use ($cartItem) {
            return $element->getDestinationEntity()->getId() === $cartItem->getDestinationEntity()->getId() && $element::class === $cartItem::class;
        });
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilteredItems(CartItem $newItem): ArrayCollection
    {
        return $this->getItems()->filter(function (CartItem $cartItem) use ($newItem) {
            return $cartItem->getDestinationEntity()->getId() === $newItem->getDestinationEntity()->getId() && $cartItem::class === $newItem::class;
        });
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function prePersist(): void
    {
        $this->createdAt = new DateTime('now');
    }

    #[PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTime('now');
    }
}
