<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\SubscriptionPlan;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function assert;

/**
 * @template TAttribute of 'order'
 * @template TSubject of SubscriptionPlan
 * @extends  Voter<'order', SubscriptionPlan>
 */
final class OrderSubscriptionPlanVoter extends Voter
{
    public const ORDER_SUBSCRIPTION_PLAN = 'ORDER_SUBSCRIPTION_PLAN';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::ORDER_SUBSCRIPTION_PLAN === $attribute && $subject instanceof SubscriptionPlan;
    }

    /** @param SubscriptionPlan $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        assert($user instanceof ShopUserInterface);

        $planSubscriptionTier = $subject->getTier();
        $userSubscriptionTier = $user->getSubscription()?->getTier();

        if (null === $userSubscriptionTier) {
            return false;
        }

        return $planSubscriptionTier > $userSubscriptionTier->value();
    }
}
