<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Order;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class OrderStatusVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Order && 'view' === $attribute && Order::PENDING === $subject->getStatus();
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return Order::PENDING === $subject->getStatus();
    }
}
