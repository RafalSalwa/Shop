<?php

namespace App\Security;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
    final public const ADD_TO_CART = "ADD_TO_CART";

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::ADD_TO_CART && $subject instanceof Product;
    }

    /**
     * @param Product $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }
        $requiredSubscription = $subject->getRequiredSubscription();
        $userSubscription = $user->getSubscription();

        if (!$requiredSubscription || !$userSubscription) {
            return false;
        }
        return $userSubscription->getRequiredLevel() >= $requiredSubscription->getId();
    }
}