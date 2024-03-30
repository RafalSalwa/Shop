<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\Order;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function assert;

/**
 * @template TAttribute of 'view'
 * @template TSubject of Order
 * @extends  Voter<'view', Order|null>
 */
final class OrderStatusVoter extends Voter
{
    /** @param Order|null $subject */
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
        assert($user instanceof ShopUserInterface);

        if ($user->getId() !== $subject->getUserId()) {
            throw new UnauthorizedHttpException('You cannot view this order.');
        }

        return true;
    }
}
