<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\CartService;
use App\Tests\CartAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    use CartAssertionsTrait;

    /**
     * @throws \Exception
     */
    public function testCartIsEmpty(): void
    {
        $client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('interview');
        $client->loginUser($testUser);

        /** @var CartService $cartService */
        $cartService = static::getContainer()->get(CartService::class);
        $cartService->clearCart();
        $crawler = $client->request('GET', '/cart');

        $this->assertResponseIsSuccessful();
        $this->assertCartIsEmpty($crawler);
    }

    /**
     * @throws \Exception
     */
    public function testAddProductToCart(): void
    {
        $client = $this->getClient();
        $crawler = $client->request('GET', '/cart/add/product/75');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();

        $cartService = static::getContainer()->get(CartService::class);
        $product = $this->getTestProduct();

        $crawler = $client->request('POST', '/cart');
        $this->assertResponseIsSuccessful();

        $this->assertCartItemsCountEquals($crawler, 1);
        $this->assertCartContainsProductWithQuantity($crawler, $product->getName(), 1);
        $this->assertCartTotalEquals($crawler, $product->getUnitPrice());

        /** @var CartService $cartService */
        $cartService = static::getContainer()->get(CartService::class);
        $cartService->clearCart();
        $this->assertSame(0, $cartService->getCurrentCart()->getItems()->count(), 'Cart should be empty right now');
    }

    /**
     * @throws \Exception
     */
    private function getClient(bool $withLoggedUser = true): KernelBrowser
    {
        $client = static::createClient([], ['HTTPS' => true]);
        if ($withLoggedUser) {
            /** @var UserRepository $userRepository */
            $userRepository = static::getContainer()->get(UserRepository::class);
            $testUser = $userRepository->findOneByUsername('interview');
            $client->loginUser($testUser);
        }

        return $client;
    }

    /**
     * @throws \Exception
     */
    private function getTestProduct(): Product
    {
        /** @var ProductRepository $productRepository */
        $productRepository = static::getContainer()->get(ProductRepository::class);

        return $productRepository->find(75);
    }
}
