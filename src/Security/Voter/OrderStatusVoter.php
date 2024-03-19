<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Order;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function is_null;
use function is_subclass_of;

final class OrderStatusVoter extends Voter
{
    /** @param Order $subject */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Order && 'view' === $attribute && Order::PENDING === $subject->getStatus();
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (false === is_subclass_of($subject, Order::class)) {
            return false;
        }
        if (true === is_null($token->getUser())) {
            return false;
        }
        if ('view' !== $attribute) {
            return false;
        }

        return Order::PENDING === $subject->getStatus();
    }
}
