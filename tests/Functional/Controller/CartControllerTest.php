<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Controller\CartController;
use App\Entity\Product;
use App\Model\User;
use App\Repository\ProductRepository;
use App\Service\CartService;
use App\Tests\Helpers\CartAssertionsTrait;
use App\Tests\Helpers\TokenTestHelperTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Throwable;

use function assert;
use function dd;

#[CoversClass(className: CartController::class)]
final class CartControllerTest extends WebTestCase
{
    use CartAssertionsTrait;
    use TokenTestHelperTrait;

    protected function setUp(): void
    {
        parent::setUp();

    }

    public function testCartIsEmpty(): void
    {
        //        try {
        //            $client = self::createClient();
        //
        //            $testUser = new User('test@test.com');
        //            $testUser->setToken($this->getToken());
        //            $testUser->setRefreshToken($this->getToken());
        //            $client->loginUser($testUser);
        //
        //            $cartService = self::getContainer()->get(CartService::class);
        //            assert($cartService instanceof CartService);
        //            $cartService->clearCart();
        //            $crawler = $client->request('GET', '/cart');
        //
        //            $this->assertResponseIsSuccessful();
        //            $this->assertCartIsEmpty($crawler);
        //        } catch (Throwable $e) {
        //            dd($e->getMessage(), $e->getTraceAsString());
        //        }
    }

    public function testAddProductToCart(): void
    {
        //        $client = $this->getClient();
        //
        //        $cartService = self::getContainer()->get(CartService::class);
        //        $cartService->clearCart();
        //        self::assertSame(0, $cartService->getCurrentCart()->getItems()->count(), 'Cart should be empty right now');
        //
        //        $client->request('GET', '/cart/add/product/75');
        //        $client->followRedirect();
        //        $this->assertResponseIsSuccessful();
        //
        //        $product = $this->getTestProduct()
        //            ->toCartItem()
        //        ;
        //
        //        $crawler = $client->request('POST', '/cart');
        //        $this->assertResponseIsSuccessful();
        //        $this->assertCartItemsCountEquals($crawler, 1);
        //        $this->assertCartContainsProductWithQuantity($crawler, $product->getDisplayName(), 1);
        //        $this->assertCartTotalEquals($crawler, $product->getReferenceEntity()->getPrice());
    }

    //    public function getClient(bool $withLoggedUser = true): KernelBrowser
    //    {
    //        $client = self::createClient(
    //            [],
    //            ['HTTPS' => true],
    //        );
    //        if ($withLoggedUser) {
    //            $testUser = self::getContainer()->get(UserRepository::class)->findOneByUsername('interview');
    //            $client->loginUser($testUser);
    //        }
    //
    //        return $client;
    //    }
    //
    //    public function getTestProduct(): Product
    //    {
    //        $productRepository = self::getContainer()->get(ProductRepository::class);
    //        assert($productRepository instanceof ProductRepository);
    //
    //        return $productRepository->find(75);
    //    }
}
