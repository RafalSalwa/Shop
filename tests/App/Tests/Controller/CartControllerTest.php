<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\CartService;
use App\Tests\CartAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
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

    public function testClearCart()
    {
        $client = static::createClient();
        $this->addRandomProductToCart($client);

        // Go to the cart page
        $client->request('GET', '/cart');

        // Clears the cart
        $client->submitForm('Clear');
        $crawler = $client->followRedirect();

        $this->assertCartIsEmpty($crawler);
    }

    public function testRemoveProductFromCart()
    {
        $client = static::createClient();
        $product = $this->addRandomProductToCart($client);

        // Go to the cart page
        $client->request('GET', '/cart');

        // Removes the product from the cart
        $client->submitForm('Remove');
        $crawler = $client->followRedirect();

        $this->assertCartNotContainsProduct($crawler, $product['name']);
    }

    public function testUpdateQuantity()
    {
        $client = static::createClient();
        $product = $this->addRandomProductToCart($client);

        // Go to the cart page
        $crawler = $client->request('GET', '/cart');

        // Updates the quantity
        $cartForm = $crawler->filter('.col-md-8 form')->form([
            'cart[items][0][quantity]' => 4,
        ]);
        $client->submit($cartForm);
        $crawler = $client->followRedirect();

        $this->assertCartTotalEquals($crawler, $product['price'] * 4);
        $this->assertCartContainsProductWithQuantity($crawler, $product['name'], 4);
    }

    private function getRandomProduct(AbstractBrowser $client): array
    {
        $crawler = $client->request('GET', '/');
        $productNode = $crawler->filter('.card')->eq(rand(0, 9));
        $productName = $productNode->filter('.card-title')->getNode(0)->textContent;
        $productPrice = (float)$productNode->filter('span.h5')->getNode(0)->textContent;
        $productLink = $productNode->filter('.btn-dark')->link();

        return [
            'name' => $productName,
            'price' => $productPrice,
            'url' => $productLink->getUri(),
        ];
    }
}