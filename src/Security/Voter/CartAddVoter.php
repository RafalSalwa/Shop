<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Product;
use App\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string,Product>
 */
class CartAddVoter extends Voter
{
    final public const ADD_TO_CART = 'ADD_TO_CART';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::ADD_TO_CART === $attribute && $subject instanceof Product;
    }

    /**
     * @param Product $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (! $user instanceof User) {
            return false;
        }

        $productRequiredSubscription = $subject->getRequiredSubscription();
        $userSubscription = $user->getSubscription();

        return $userSubscription->getRequiredLevel() >= $productRequiredSubscription->getId();
    }
}
