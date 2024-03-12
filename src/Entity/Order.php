<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\PreUpdateEventArgs;
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

#[Entity(repositoryClass: OrderRepository::class)]
#[Table(name: 'orders', schema: 'interview')]
#[HasLifecycleCallbacks]
class Order
{
    final public const PENDING = 'pending';

    final public const PROCESSING = 'processing';

    final public const COMPLETED = 'completed';

    final public const CANCELLED = 'cancelled';

    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'order_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    #[Column(name: 'status', type: Types::STRING, length: 25)]
    private string $status;

    #[Column(name: 'amount', type: Types::INTEGER)]
    private int $total;

    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $createdAt;

    #[Column(name: 'user_id', type: Types::INTEGER)]
    private int $userId;

    /** @var Collection<int, Payment> */
    #[OneToMany(mappedBy: 'order', targetEntity: Payment::class, orphanRemoval: true)]
    private Collection $payments;

    /** @var Collection<int, OrderItem> */
    #[OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $items;

    #[ManyToOne(targetEntity: Address::class, cascade: ['persist'])]
    #[JoinColumn(name: 'delivery_address_id', referencedColumnName: 'address_id', unique: false)]
    private Address $deliveryAddress;

    #[ManyToOne(targetEntity: Address::class, cascade: ['persist'])]
    #[JoinColumn(name: 'biling_address_id', referencedColumnName: 'address_id', unique: false)]
    private Address $bilingAddress;

    private int $netAmount = 0;

    private int $vatAmount = 0;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->items    = new ArrayCollection();
    }

    public function getUpdatedAt(): DateTime|null
    {
        return $this->updatedAt;
    }

    public function getAddress(): Address|null
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getTotal(): string
    {
        return (string)$this->total;
    }

    public function setTotal(string $total): void
    {
        $this->total = (int)$total;
    }

    public function addItem(OrderItem $orderItem): void
    {
        $orderItem->setOrder($this);
        $this->getItems()->add($orderItem);
    }

    /** @return Collection<int, OrderItem> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setItems(ArrayCollection $items): void
    {
        $this->items = $items;
    }

    public function removeItems(OrderItem $orderItem): void
    {
        $this->items->removeElement($orderItem);
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

    #[PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    #[PreUpdate]
    public function preUpdate(PreUpdateEventArgs $preUpdateEventArgs): void
    {
        $this->updatedAt = new DateTime('now');
    }

    public function addPayment(Payment|TValue $payment): void
    {
        $payment->setOrder($this);
        $this->payments[] = $payment;
    }

    public function getPayments(): Collection|null
    {
        return $this->payments;
    }

    public function getLastPayment(): Payment|null
    {
        return $this->payments?->last();
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getDeliveryAddress(): Address
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(Address $address): void
    {
        $this->deliveryAddress = $address;
    }

    public function getBilingAddress(): Address
    {
        return $this->bilingAddress;
    }

    public function setBilingAddress(Address $address): void
    {
        $this->bilingAddress = $address;
    }
}
