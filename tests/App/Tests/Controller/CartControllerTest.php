<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\CartService;
use App\Tests\CartAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use UserLogInHelperTrait;

class CartControllerTest extends WebTestCase
{
    use CartAssertionsTrait;


    public function testCartIsEmpty()
    {
        $client = static::createClient();

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

    public function testAddProductToCart()
    {
        $client = $this->getClient();
        $crawler = $client->request('GET', '/cart/add/product/75');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();

        $cartService = static::getContainer()->get(CartService::class);
        $cart = $cartService->getCurrentCart();
        $product = $this->getTestProduct();

        $crawler = $client->request('POST', '/cart');
        $this->assertResponseIsSuccessful();

        $this->assertCartItemsCountEquals($crawler, 1);
        $this->assertCartContainsProductWithQuantity($crawler, $product->getName(), 1);
        $this->assertCartTotalEquals($crawler, $product->getUnitPrice());

        /** @var CartService $cartService */
        $cartService = static::getContainer()->get(CartService::class);
        $cartService->clearCart();
        $this->assertEquals(0, $cartService->getCurrentCart()->getItems()->count(), "Cart should be empty right now");
    }

    private function getClient($withLoggedUser = true)
    {
        $client = static::createClient([], ['HTTPS' => true]);
        if ($withLoggedUser) {
            $userRepository = static::getContainer()->get(UserRepository::class);
            $testUser = $userRepository->findOneByUsername('interview');
            $client->loginUser($testUser);
        }
        return $client;
    }

    private function getTestProduct()
    {
        $productRepository = static::getContainer()->get(ProductRepository::class);
        return $productRepository->findOneBy(["id" => 75]);
    }
}