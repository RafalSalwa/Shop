<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CartItem;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Factory\CartItemFactory;
use App\Manager\CartManager;
use App\Requests\CartAddJsonRequest;
use App\Security\CartItemVoter;
use App\Service\CartCalculator;
use App\Service\CartService;
use App\Service\ProductStockService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/** @see \App\Tests\Integration\CartControllerTest */
#[Route('/cart', name:'cart_')]
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
            $this->denyAccessUnlessGranted(CartItemVoter::ADD_TO_CART, $item);

            $cartService->add($item,$quantity);
            $this->addFlash('info', 'successfully added ' . $item->getDisplayName() . ' to cart');

        } catch (ItemNotFoundException $pnf) {
            $this->addFlash('error', $pnf->getMessage());

            return $this->redirectToRoute(
                $type . '_index',
                [
                    'id' => $id,
                    'page' => 1,
                ],
            );
        } catch (AccessDeniedException) {
            $this->addFlash(
                'error',
                'You cannot add this product to cart with current subscription. Consider upgrade:)',
            );
        }

        return $this->redirectToRoute(
            $item->getTypeName() . '_details',
            ['id' => $id],
        );
    }

    #[Route('/add', name: 'add_post', methods: ['POST'])]
    public function post(
        #[MapRequestPayload] CartAddJsonRequest $cartAddRequest,
        CartService $cartService,
        CartItemFactory $cartItemFactory,
    ): RedirectResponse {
        try {
            $item = $cartItemFactory->create($cartAddRequest->getType(), $cartAddRequest->getId());
            $this->denyAccessUnlessGranted(CartItemVoter::ADD_TO_CART, $item);

            $cartService->add($item,$cartAddRequest->getQuantity());
            $this->addFlash('info', 'successfully added ' . $item->getDisplayName() . ' to cart');
            dd($cartService->getCurrentCart()->getItems());
        } catch (AccessDeniedException $ade) {
            dd($ade->getMessage());
            $this->addFlash(
                'error',
                'You cannot add this product to cart with current subscription. Consider upgrade:)',
            );
        } catch(Exception $e){
            dd($e->getMessage(), $e->getTraceAsString(), $e->getMessage(), $e->getMessage());
        }

        return $this->redirectToRoute(
            $item->getTypeName() . 's_details',
            ['id' => $cartAddRequest->getId()],
        );
    }
    #[Route('/remove/{id}', name: 'remove')]
    public function removeFromCart(
        CartItem $item,
        CartService $cartService,
        ProductStockService $productStockService,
    ): RedirectResponse {
        $cart = $cartService->getCurrentCart();

        try {
            $cartService->removeItemIfExists($item);
            $productStockService->restoreStock($item);

            $cartService->save($cart);

            $this->addFlash('info', 'successfully removed ' . $item->getItemName() . ' from cart');
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
        $cartService->setDefaultDeliveryAddress($deliveryAddressId);

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
