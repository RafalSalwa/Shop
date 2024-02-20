<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\SubscriptionPlanCartItem;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SubscriptionPlanVoter extends Voter
{
    final public const ADD_TO_CART = 'ADD_TO_CART';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::ADD_TO_CART === $attribute && $subject instanceof SubscriptionPlanCartItem;
    }

    /**
     * @param SubscriptionPlanCartItem $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (! $user instanceof User) {
            return false;
        }
        $requiredSubscription = $subject->getReferencedEntity()
            ->getId()
        ;
        $userSubscription = $user->getSubscription()
            ->getSubscriptionPlan()
        ;

        if (! $requiredSubscription || ! $userSubscription) {
            return false;
        }

        return $userSubscription->getId() < $requiredSubscription;
    }
}
