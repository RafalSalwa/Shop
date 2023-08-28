<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity(repositoryClass: CartItemRepository::class)]
#[HasLifecycleCallbacks]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'item_type', type: Types::STRING, length: 30)]
class CartItem implements SerializerInterface, CartItemInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'cart_item_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    #[ManyToOne(targetEntity: Cart::class)]
    #[JoinColumn(name: 'cart_id', referencedColumnName: 'cart_id')]
    #[Groups("cart_item")]
    private ?Cart $cart;

    #[Column(name: 'quantity', type: Types::INTEGER, nullable: false, options: ['default' => '1'])]
    private int $quantity;
    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;
    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime $updatedAt;

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
    public function prePersist()
    {
        $this->setCreatedAt(new DateTime('now'));
        $this->setCreatedAt(new DateTime('now'));
    }

    #[PreUpdate]
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $this->value = 'changed from preUpdate callback!';
    }

    public function setUser(UserInterface $getUser)
    {
    }

    public function serialize($data, string $format, ?SerializationContext $context = null, ?string $type = null): string
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'verified' => $this->verified,
            'active' => $this->active,
        ];
    }

    public function deserialize(string $data, string $type, string $format, ?DeserializationContext $context = null)
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

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

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
}
