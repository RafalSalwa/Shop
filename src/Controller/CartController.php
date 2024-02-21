<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CartItem;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Factory\CartItemFactory;
use App\Manager\CartManager;
use App\Requests\CartAddJsonRequest;
use App\Security\Voter\CartAddVoter;
use App\Security\Voter\CartItemVoter;
use App\Service\CartCalculator;
use App\Service\CartService;
use App\Service\ProductStockService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Throwable;
use function dd;

/** @see \App\Tests\Integration\CartControllerTest */
#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/add/{type}/{id}/{quantity}', name: 'add')]
    public function addToCart(
        #[ValueResolver('cart_item_type')]
        string $type,
        int $id,
        int $quantity,
        CartService $cartService,
        CartItemFactory $cartItemFactory,
    ): RedirectResponse {
        try {
            $item = $cartItemFactory->create($type, $id);
            $this->denyAccessUnlessGranted(CartAddVoter::ADD_TO_CART, $item);

            $cartService->add($item, $quantity);
        } catch (Throwable $throwable) {
            dd($throwable::class, $throwable->getMessage());
        }

        return $this->redirectToRoute(
            $item->getTypeName() . '_details',
            ['id' => $id],
        );
    }

    #[Route('/add', name: 'add_post', methods: ['POST'])]
    public function post(
        #[MapRequestPayload]
        CartAddJsonRequest $cartAddJsonRequest,
        CartService $cartService,
        CartItemFactory $cartItemFactory,
    ): RedirectResponse {
        try {
            $item = $cartItemFactory->create($cartAddJsonRequest->getType(), $cartAddJsonRequest->getId());
            $this->denyAccessUnlessGranted(CartItemVoter::ADD_TO_CART, $item);

            $cartService->add($item, $cartAddJsonRequest->getQuantity());
            $this->addFlash('info', 'successfully added ' . $item->getDisplayName() . ' to cart');
            dd($cartService->getCurrentCart()->getItems());
        } catch (AccessDeniedException $ade) {
            dd($ade->getMessage());
            $this->addFlash(
                'error',
                'You cannot add this product to cart with current subscription. Consider upgrade:)',
            );
        } catch (Throwable $e) {
            dd($e->getMessage(), $e->getTraceAsString(), $e->getMessage(), $e->getMessage());
        }

        return $this->redirectToRoute(
            $item->getTypeName() . 's_details',
            ['id' => $cartAddJsonRequest->getId()],
        );
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function removeFromCart(
        CartItem $cartItem,
        CartService $cartService,
        ProductStockService $productStockService,
    ): RedirectResponse {
        $cart = $cartService->getCurrentCart();

        try {
            $cartService->removeItemIfExists($cartItem);
            $productStockService->restoreStock($cartItem);

            $cartService->save($cart);

            $this->addFlash('info', 'successfully removed ' . $cartItem->getItemName() . ' from cart');
        } catch (ItemNotFoundException $pnf) {
            $this->addFlash('error', $pnf->getMessage());
        } catch (ProductStockDepletedException $psd) {
            $this->addFlash('error', $psd->getMessage());
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/set_delivery_address', name: 'delivery_address_set')]
    public function setDeliveryAddress(Request $request, CartService $cartService): Response
    {
        $deliveryAddressId = $request->request->get('addrId');
        $cartService->useDefaultDeliveryAddress($deliveryAddressId);

        return new Response('ok');
    }

    #[Route('/', name: 'index')]
    public function show(CartManager $cartManager, CartCalculator $cartCalculator): Response
    {
        $cart = $cartManager->getCurrentCart();
        $payment = $cartCalculator->calculatePayment($cart);

        return $this->render(
            'cart/index.html.twig',
            [
                'cart' => $cart,
                'payment' => $payment,
            ],
        );
    }
}
