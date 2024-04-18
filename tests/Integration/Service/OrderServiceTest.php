<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Entity\Cart;
use App\Exception\ItemNotFoundException;
use App\Model\User;
use App\Service\OrderService;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(className: OrderService::class)]
final class OrderServiceTest extends WebTestCase
{
    private KernelBrowser $client;

    private OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Symfony client to make requests
        $this->client = self::createClient();
        $this->orderService = self::getContainer()->get(OrderService::class);
    }

    public function testCreatePendingOrder(): void
    {
        $this->logInUser();

        $cart = new Cart(1);

        $this->expectException(ItemNotFoundException::class);
        $this->orderService->createPending($cart);
    }

    private function logInUser(): void
    {
        $this->client->loginUser(new User('test@test.com'));
    }
}
