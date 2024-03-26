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
class Subscription
{
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    #[Column(name: 'subscription_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    #[ManyToOne(targetEntity: 'SubscriptionPlan')]
    #[JoinColumn(name: 'subscription_plan_id', referencedColumnName: 'plan_id', nullable: false)]
    private SubscriptionPlan $subscriptionPlan;

    #[Column(name: 'user_id', type: Types::INTEGER, nullable: false)]
    private int $userId;

    #[Column(name: 'tier', type: Types::SMALLINT, nullable: false, enumType: SubscriptionTier::class)]
    private SubscriptionTier $subscriptionTier;

    #[Column(name: 'is_active', type: Types::BOOLEAN, nullable: false, options: ['default' => true])]
    private bool $isActive = true;

    #[Column(
        name: 'created_at',
        type: Types::DATETIME_IMMUTABLE,
        nullable: false,
        options: ['default' => 'CURRENT_TIMESTAMP'],
    )]
    private DateTimeImmutable $createdAt;

    #[Column(
        name: 'starts_at',
        type: Types::DATETIME_IMMUTABLE,
        nullable: false,
        options: ['default' => 'CURRENT_TIMESTAMP'],
    )]
    private DateTimeImmutable $startsAt;

    #[Column(name: 'ends_at', type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private DateTimeImmutable $endsAt;

    public function __construct(int $userId, SubscriptionPlan $plan)
    {
        $this->userId = $userId;
        $this->subscriptionPlan = $plan;
        $this->subscriptionTier = SubscriptionTier::from($plan->getTier());

        $dateTimeImmutable = new DateTimeImmutable();
        $this->createdAt = $dateTimeImmutable;
        $this->startsAt = $dateTimeImmutable;

        $this->endsAt = $dateTimeImmutable->add(new DateInterval('P30D'));
    }

    public function getTier(): SubscriptionTier
    {
        return $this->subscriptionTier;
    }

    public function getPlan(): SubscriptionPlan|null
    {
        return $this->subscriptionPlan;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getStartsAt(): DateTime|null
    {
        return $this->startsAt;
    }

    public function getEndsAt(): DateTime|null
    {
        return $this->endsAt;
    }

    public function getRequiredLevel(): int
    {
        return $this->getTier()->value;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
