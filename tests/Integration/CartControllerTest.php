<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\CartService;
use App\Tests\CartAssertionsTrait;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversNothing]
final class CartControllerTest extends WebTestCase
{
    use CartAssertionsTrait;

    public function testCartIsEmpty(): void
    {
        try {
            $client = self::createClient();
            $userRepository = self::getContainer()->get(UserRepository::class);

            $testUser = $userRepository->findOneByUsername('interview');
            $client->loginUser($testUser);

            /** @var CartService $cartService */
            $cartService = self::getContainer()->get(CartService::class);
            $cartService->clearCart();
            $crawler = $client->request('GET', '/cart');

            $this->assertResponseIsSuccessful();
            $this->assertCartIsEmpty($crawler);
        } catch (Exception $exception) {
            dd($exception->getMessage(), $exception->getPrevious()->getMessage(), $exception->getTraceAsString());
        }
    }

    public function testAddProductToCart(): void
    {
        $kernelBrowser = $this->getClient();

        $cartService = self::getContainer()->get(CartService::class);
        $cartService->clearCart();
        $this->assertSame(0, $cartService->getCurrentCart()->getItems()->count(), 'Cart should be empty right now');

        $kernelBrowser->request('GET', '/cart/add/product/75');
        $kernelBrowser->followRedirect();
        $this->assertResponseIsSuccessful();

        $cartItem = $this->getTestProduct()
            ->toCartItem()
        ;

        $crawler = $kernelBrowser->request('POST', '/cart');
        $this->assertResponseIsSuccessful();
        $this->assertCartItemsCountEquals($crawler, 1);
        $this->assertCartContainsProductWithQuantity($crawler, $cartItem->getDisplayName(), 1);
        $this->assertCartTotalEquals($crawler, $cartItem->getReferenceEntity()->getPrice());
    }

    private function getClient(bool $withLoggedUser = true): KernelBrowser
    {
        $kernelBrowser = self::createClient([], [
            'HTTPS' => true,
        ]);
        if ($withLoggedUser) {
            $testUser = self::getContainer()->get(UserRepository::class)->findOneByUsername('interview');
            $kernelBrowser->loginUser($testUser);
        }

        return $kernelBrowser;
    }

    private function getTestProduct(): Product
    {
        /** @var ProductRepository $productRepository */
        $productRepository = self::getContainer()->get(ProductRepository::class);

        return $productRepository->find(75);
    }
}
