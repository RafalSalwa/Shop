<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\SubscriptionTier;
use App\Repository\SubscriptionRepository;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: SubscriptionRepository::class)]
#[Table(name: 'subscription', schema: 'interview')]
#[HasLifecycleCallbacks]
class Subscription
{
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    #[Column(name: 'subscription_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    #[ManyToOne(targetEntity: 'SubscriptionPlan')]
    #[JoinColumn(name: 'subscription_plan_id', referencedColumnName: 'plan_id', nullable: true)]
    private SubscriptionPlan|null $subscriptionPlan = null;

    #[Column(name: 'user_id', type: Types::INTEGER, nullable: true)]
    private int|null $userId = null;

    #[Column(name: 'tier', type: Types::SMALLINT, nullable: false, enumType: SubscriptionTier::class)]
    private SubscriptionTier $subscriptionTier = SubscriptionTier::Freemium;

    #[Column(name: 'is_active', type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isActive = true;

    #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $createdAt;

    #[Column(
        name: 'starts_at',
        type: Types::DATETIME_MUTABLE,
        nullable: true,
        options: ['default' => 'CURRENT_TIMESTAMP'],
    )]
    private DateTime|null $startsAt = null;

    #[Column(name: 'ends_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime|null $endsAt = null;

    public function __construct()
    {
        $dateTime = new DateTime();
        $dateTime->add(new DateInterval('P30D'));

        $this->endsAt = $dateTime;
    }

    public function getPlan(): SubscriptionPlan|null
    {
        return $this->subscriptionPlan;
    }

    public function setPlan(SubscriptionPlan $subscriptionPlan): void
    {
        $this->subscriptionPlan = $subscriptionPlan;
    }

    public function setTier(int $tier): self
    {
        $this->subscriptionTier = $tier;

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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStartsAt(): DateTime|null
    {
        return $this->startsAt;
    }

    public function setStartsAt(DateTime|null $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): DateTime|null
    {
        return $this->endsAt;
    }

    public function setEndsAt(DateTime|null $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getRequiredLevel(): int
    {
        return $this->getTier()->value;
    }

    public function getTier(): SubscriptionTier
    {
        return $this->subscriptionTier;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    #[PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
}
