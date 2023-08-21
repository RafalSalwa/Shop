<?php

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
    public const PENDING = 'pending';
    public const PROCESSING = 'processing';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'order_id', type: Types::INTEGER, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'order_orderID_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[Column(name: 'status', type: Types::STRING, length: 25)]
    private string $status;

    #[Column(name: 'amount', type: Types::SMALLINT)]
    private int $amount;
    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $updatedAt = null;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[JoinColumn(name: "user_id", referencedColumnName: 'user_id', nullable: true)]
    private UserInterface $user;

    #[ManyToOne(targetEntity: Address::class, inversedBy: 'orders')]
    #[JoinColumn(name: "address_id", referencedColumnName: 'address_id', nullable: true)]
    private ?Address $address;

    #[OneToMany(mappedBy: 'order', targetEntity: Payment::class, orphanRemoval: true)]
    private ?Collection $payments;

    #[OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, orphanRemoval: true, cascade: ["persist"])]
    private $items;

    public function getId()
    {
        return $this->id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): Order
    {
        $this->user = $user;
        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): Order
    {
        $this->amount = $amount;
        return $this;
    }


    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setItems(ArrayCollection $items): Order
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

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    #[PrePersist]
    public function prePersist()
    {
        $this->createdAt = new DateTime('now');
    }

    #[PreUpdate]
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $this->updatedAt = new DateTime('now');
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
}
