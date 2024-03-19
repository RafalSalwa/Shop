<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\Product;
use App\Enum\CartOperationEnum;
use App\Repository\SubscriptionRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function is_subclass_of;

/** @extends Voter<string,Product> */
final class AddToCartVoter extends Voter
{
    public function __construct(private SubscriptionRepository $subscriptionRepository)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return CartOperationEnum::ADD_TO_CART->value === $attribute && $subject instanceof Product;
    }

    /** @param Product $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (false === is_subclass_of($subject, Product::class)) {
            return false;
        }
        if (CartOperationEnum::ADD_TO_CART->value !== $attribute) {
            return false;
        }

        $user = $token->getUser();
        if (null === $user && false === $user instanceof ShopUserInterface) {
            return false;
        }

        $productSubscription = $subject->getRequiredSubscription();
        $userSubscription = $this->subscriptionRepository->findForUser($token->getUser()->getToken()->getSub());

        return $userSubscription->getRequiredLevel() >= $productSubscription->getId();
    }
}
