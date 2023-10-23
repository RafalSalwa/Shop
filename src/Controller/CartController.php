<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CartItem;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Exception\TooManySubscriptionsException;
use App\Factory\CartItemFactory;
use App\Manager\CartManager;
use App\Security\CartItemVoter;
use App\Service\CartCalculator;
use App\Service\CartService;
use App\Service\ProductStockService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @see \App\Tests\Controller\CartControllerTest
 */
class CartController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/cart/add/{type}/{id}', name: 'cart_add')]
    public function addToCart(
        #[ValueResolver('cart_item_type')]
        string $type,
        int $id,
        CartService $cartService,
        CartItemFactory $cartItemFactory
    ): RedirectResponse {
        try {
            $item = $cartItemFactory->createCartItem($type, $id);
            $this->denyAccessUnlessGranted(CartItemVoter::ADD_TO_CART, $item);

            $cartService->add($item);
            $this->addFlash('info', 'successfully added '.$item->getDisplayName().' to cart');
        } catch (ItemNotFoundException $pnf) {
            $this->addFlash('error', $pnf->getMessage());

            return $this->redirectToRoute($type.'_index', ['id' => $id, 'page' => 1]);
        } catch (ProductStockDepletedException $psd) {
            $this->addFlash('error', $psd->getMessage());
        } catch (AccessDeniedException) {
            $this->addFlash(
                'error',
                'You cannot add this product to cart with current subscription. Consider upgrade:)'
            );
        } catch (TooManySubscriptionsException $subex) {
            $this->addFlash('error', $subex->getMessage());
        }

        return $this->redirectToRoute($item->getTypeName().'_details', ['id' => $id]);
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function removeFromCart(
        CartItem $item,
        CartService $cartService,
        ProductStockService $productStockService
    ): RedirectResponse {
        $cart = $cartService->getCurrentCart();
        try {
            $cartService->removeItemIfExists($item);
            $productStockService->restoreStock($item);

            $cartService->save($cart);

            $this->addFlash('info', 'successfully removed '.$item->getItemName().' from cart');
        } catch (ItemNotFoundException $pnf) {
            $this->addFlash('error', $pnf->getMessage());
        } catch (ProductStockDepletedException $psd) {
            $this->addFlash('error', $psd->getMessage());
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/set_delivery_address', name: 'cart_delivery_address_set')]
    public function deliveryAddress(Request $request, CartService $cartService): Response
    {
        $deliveryAddressId = $request->request->get('addrId');
        $cartService->setDefaultDeliveryAddress($deliveryAddressId);

        return new Response('ok');
    }

    #[Route('/cart', name: 'cart_index')]
    public function show(CartManager $cartManager, CartCalculator $cartCalculator): Response
    {
        $cart = $cartManager->getCurrentCart();
        $payment = $cartCalculator->calculatePayment($cart);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'payment' => $payment,
        ]);
    }
}
