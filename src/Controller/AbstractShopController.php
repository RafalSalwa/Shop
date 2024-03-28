<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contracts\ShopUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function assert;

abstract class AbstractShopController extends AbstractController
{
    final protected function getUserId(): int
    {
        return $this->getShopUser()->getId();
    }

    final protected function getShopUser(): ShopUserInterface
    {
        $user = $this->getUser();
        assert($user instanceof ShopUserInterface);

        return $user;
    }
}
