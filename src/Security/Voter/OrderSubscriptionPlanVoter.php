<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\SubscriptionPlan;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function is_null;

/**
 * @template TAttribute of 'order'
 * @template TSubject of SubscriptionPlan
 * @extends  Voter<'order', SubscriptionPlan>
 */
final class OrderSubscriptionPlanVoter extends Voter
{
    public const ORDER_SUBSCRIPTION_PLAN = 'ORDER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::ORDER_SUBSCRIPTION_PLAN === $attribute && $subject instanceof SubscriptionPlan;
    }

    /** @param SubscriptionPlan $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (self::ORDER_SUBSCRIPTION_PLAN !== $attribute) {
            return false;
        }
        $user = $token->getUser();

        if (null === $user && false === $user instanceof ShopUserInterface) {
            return false;
        }

        $planSubscriptionTier = $subject->getTier();
        $userSubscriptionTier = $user->getSubscription()->getTier();

        if (true === is_null($planSubscriptionTier) || true === is_null($userSubscriptionTier)) {
            return false;
        }

        return $planSubscriptionTier > $userSubscriptionTier->value();
    }
}
