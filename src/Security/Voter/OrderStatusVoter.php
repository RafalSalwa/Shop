<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Order;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @template TAttribute of 'view'
 * @template TSubject of Order
 * @extends  Voter<'view', Order>
 */
final class OrderStatusVoter extends Voter
{
    /** @param Order $subject */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return 'view' === $attribute && $subject instanceof Order && Order::PENDING === $subject->getStatus();
    }

    /** @param Order $subject */
    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (null === $user) {
            return false;
        }

        if ($user->getId() !== $subject->getUserId()) {
            throw new UnauthorizedHttpException('You cannot view this order.');
        }

        return true;
    }
}
