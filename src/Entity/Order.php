<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
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
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\UserInterface;

#[Entity(repositoryClass: OrderRepository::class)]
#[Table(name: 'orders')]
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
    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'user_id', nullable: true)]
    private UserInterface $user;

    #[ManyToOne(targetEntity: Address::class, inversedBy: 'orders')]
    #[JoinColumn(name: 'address_id', referencedColumnName: 'address_id', nullable: true)]
    private ?Address $address = null;

    #[OneToMany(mappedBy: 'order', targetEntity: Payment::class, orphanRemoval: true)]
    private ?Collection $payments = null;

    #[OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?Collection $items = null;

    private int $netAmount = 0;
    private int $vatAmount = 0;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address)
    {
        $this->address = $address;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAmount(bool $userFriendly = false): int
    {
        if ($userFriendly) {
            return $this->amount / 100;
        }

        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getNetAmount(bool $humanFriendly)
    {
        if ($humanFriendly) {
            return number_format($this->netAmount / 100, 2, '.', ' ');
        }

        return $this->netAmount;
    }

    public function setNetAmount(int $amount)
    {
        $this->netAmount = $amount;
    }

    public function getVatAmount(bool $humanFriendly)
    {
        if ($humanFriendly) {
            return number_format($this->vatAmount / 100, 2, '.', ' ');
        }

        return $this->vatAmount;
    }

    public function setVatAmount(int $amount)
    {
        $this->vatAmount = $amount;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setItems(ArrayCollection $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function addItem(OrderItem $item): void
    {
        $item->setOrder($this);
        $this->items[] = $item;
    }

    public function removeItems(OrderItem $item): void
    {
        $this->items->removeElement($item);
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
    public function prePersist()
    {
        $this->createdAt = new \DateTime('now');
    }

    #[PreUpdate]
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $this->updatedAt = new \DateTime('now');
    }

    public function addPayment($payment)
    {
        $payment->setOrder($this);
        $this->payments[] = $payment;
    }

    public function getPayments()
    {
        return $this->payments;
    }

    public function getLastPayment()
    {
        return $this->payments->last();
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
