<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\ProductCartItem;
use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CartItemVoter extends Voter
{
    final public const ADD_TO_CART = 'ADD_TO_CART';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::ADD_TO_CART === $attribute && $subject instanceof ProductCartItem;
    }

    /**
     * @param ProductCartItem $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (! $user instanceof User) {
            return false;
        }

        /** @var SubscriptionPlan $requiredSubscription */
        $requiredSubscription = $subject->getReferencedEntity()
            ->getRequiredSubscription()
        ;

        /** @var Subscription $userSubscription */
        $userSubscription = $user->getSubscription();
        if (! $requiredSubscription || ! $userSubscription) {
            return false;
        }

        return $userSubscription->getRequiredLevel() >= $requiredSubscription->getId();
    }
}
