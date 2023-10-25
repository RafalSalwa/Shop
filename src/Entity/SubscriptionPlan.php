<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PlanRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: PlanRepository::class)]
#[Table(name: 'plan', schema: 'interview')]
#[Index(columns: ['plan_name'], name: 'u_plan_idx')]
#[Cache(usage: 'READ_ONLY')]
#[HasLifecycleCallbacks]
class SubscriptionPlan implements CartInsertableInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'plan_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    #[Column(name: 'plan_name', type: Types::STRING, length: 255)]
    private string $planName;

    #[Column(name: 'description', type: Types::TEXT, nullable: false)]
    #[Assert\NotBlank(message: 'Description cannot be empty')]
    #[Assert\Length(min: 10, minMessage: 'You need to add any')]
    private string $description;

    #[Column(name: 'is_active', type: Types::BOOLEAN, options: [
        'default' => false,
    ])]
    private bool $isActive = false;

    #[Column(name: 'is_visible', type: Types::BOOLEAN, options: [
        'default' => false,
    ])]
    private bool $isVisible = false;

    #[Column(name: 'unit_price', type: Types::SMALLINT, nullable: false)]
    private int $unitPrice;

    #[Column(name: 'tier', type: Types::SMALLINT, nullable: false)]
    private int $tier;

    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: [
        'default' => 'CURRENT_TIMESTAMP',
    ])]
    private DateTime $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $updatedAt = null;

    #[Column(name: 'deleted_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $deletedAt = null;

    public function setPlanName(string $planName): self
    {
        $this->planName = $planName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUnitPrice(bool $userFriendly = false): int|float
    {
        if ($userFriendly) {
            return $this->unitPrice / 100;
        }

        return $this->unitPrice;
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

    public function getUpdatedAt(): DateTime|null
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): DateTime|null
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function toCartItem(): CartItemInterface
    {
        $cartItem = new SubscriptionPlanCartItem();
        $cartItem
            ->setReferencedEntity($this)
            ->setQuantity(1)
            ->setCreatedAt(new DateTime('now'));

        return $cartItem;
    }

    #[PrePersist]
    public function prePersist(): void
    {
        $this->setCreatedAt(new DateTime('now'));
    }

    #[PreUpdate]
    public function preUpdate(): void
    {
        $this->setUpdatedAt(new DateTime('now'));
    }

    public function getDisplayName(): string
    {
        return sprintf('%s (%s) #%d', $this->getTypeName(), $this->getName(), $this->getId());
    }

    public function getTypeName(): string
    {
        return 'plan';
    }

    public function getName(): string
    {
        return $this->planName;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
