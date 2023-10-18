<?php

namespace App\Security;

use App\Entity\Order;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OrderStatusVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Order && $attribute === 'view' && $subject->getStatus() === Order::PENDING;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        return $subject->getStatus() === Order::PENDING;
    }
}
