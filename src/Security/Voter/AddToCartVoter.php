<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Contracts\CartInsertableInterface;
use App\Entity\Contracts\ShopUserInterface;
use App\Entity\Product;
use App\Enum\CartOperationEnum;
use App\Service\SubscriptionService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function is_subclass_of;

/** @extends Voter<string,Product> */
final class AddToCartVoter extends Voter
{
    public function __construct(private SubscriptionService $service)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return CartOperationEnum::ADD->value === $attribute && is_subclass_of($subject, CartInsertableInterface::class);
    }

    /** @param Product $subject */
    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (null === $user || false === is_subclass_of($user, ShopUserInterface::class)) {
            return false;
        }

        $productSubscription = $subject->getRequiredSubscription();
        $userSubscription = $this->service->findForUser($user->getId());

        return $userSubscription->getRequiredLevel() >= $productSubscription->getId();
    }
}
