<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\SubscriptionPlan;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function assert;
use function is_null;
use function is_subclass_of;

final class OrderSubscriptionPlanVoter extends Voter
{
    final public const ORDER_SUBSCRIPTION_PLAN = 'ORDER_SUBSCRIPTION_PLAN';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::ORDER_SUBSCRIPTION_PLAN === $attribute && $subject instanceof SubscriptionPlan;
    }

    /** @param SubscriptionPlan $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        assert($user instanceof ShopUserInterface);

        if (false === is_subclass_of($user, ShopUserInterface::class)) {
            return false;
        }

        $planSubscriptionTier = $subject->getTier();
        $userSubscriptionTier = $user->getSubscription()->getTier();

        if (is_null($planSubscriptionTier) || is_null($userSubscriptionTier)) {
            return false;
        }

        return $planSubscriptionTier > $userSubscriptionTier->value();
    }
}
