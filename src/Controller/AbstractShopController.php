<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contracts\ShopUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use function assert;

#[asController]
abstract class AbstractShopController extends AbstractController
{
    protected function getShopUser(): ShopUserInterface
    {
        $user = $this->getUser();
        assert($user instanceof ShopUserInterface);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
