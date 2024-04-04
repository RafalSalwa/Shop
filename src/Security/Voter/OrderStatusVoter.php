<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\Order;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function assert;

/**
 * @template TAttribute of 'view'
 * @template TSubject of Order
 * @extends  Voter<TAttribute, TSubject>
 */
final class OrderStatusVoter extends Voter
{
    /** @param Order $subject */
    protected function supports(string $attribute, mixed $subject): bool
    {
        if ('view' === $attribute && Order::PENDING === $subject->getStatus()) {
            return (bool)self::ACCESS_GRANTED;
        }

        return (bool)self::ACCESS_ABSTAIN;
    }

    /** @param Order $subject */
    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        assert($user instanceof ShopUserInterface);

        if ($user->getId() !== $subject->getUserId()) {
            return (bool)self::ACCESS_DENIED;
        }

        return (bool)self::ACCESS_GRANTED;
    }
}
