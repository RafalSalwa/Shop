<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\Table;
use function number_format;

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
    #[SequenceGenerator(sequenceName: 'order_orderID_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[Column(name: 'status', type: Types::STRING, length: 25)]
    private string $status;

    #[Column(name: 'amount', type: Types::INTEGER)]
    private int $amount;

    #[Column(
        name: 'created_at',
        type: Types::DATETIME_MUTABLE,
        options: ['default' => 'CURRENT_TIMESTAMP'],
    )]
    private DateTime $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime|null $updatedAt = null;

    #[Column(name: 'user_id', type: Types::INTEGER)]
    private int $userId;

    private Address|null $address = null;

    /** @var Collection<int, Payment> */
    #[OneToMany(mappedBy: 'order', targetEntity: Payment::class, orphanRemoval: true)]
    private Collection|null $payments = null;

    /** @var Collection<int, OrderItem> */
    #[OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection|null $items = null;

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

    public function getAmount(): string
    {
        return (string)$this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getNetAmount(bool $humanFriendly): int|string
    {
        if ($humanFriendly) {
            return number_format(($this->netAmount / 100), 2, '.', ' ');
        }

        return $this->netAmount;
    }

    public function setNetAmount(int $amount): void
    {
        $this->netAmount = $amount;
    }

    public function getVatAmount(bool $humanFriendly): int|string
    {
        if ($humanFriendly) {
            return number_format(($this->vatAmount / 100), 2, '.', ' ');
        }

        return $this->vatAmount;
    }

    public function setVatAmount(int $amount): void
    {
        $this->vatAmount = $amount;
    }

    public function getItems(): Collection|null
    {
        return $this->items;
    }

    public function setItems(ArrayCollection $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function addItem(OrderItem $orderItem): void
    {
        $orderItem->setOrder($this);
        $this->items[] = $orderItem;
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
        $this->createdAt = new DateTime('now');
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
}
