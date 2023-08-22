<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Manager\CartManager;
use App\Service\CartCalculator;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart/add/{type}/{id}', name: 'cart_add')]
    public function addToCart(
        #[ValueResolver('cart_item_type')] string $type,
        int                                       $id,
        CartService                               $cartService,
        EntityManagerInterface                    $entityManager
    ): RedirectResponse
    {
        $entity = $entityManager->getRepository($type)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Product not found');
        }

        $item = $cartService->makeCartItem($entity);
        $cart = $cartService->getCurrentCart();
        $cart->addItem($item);

        $cartService->save($cart);
        $this->addFlash("notice", "successfully added " . $entity->getDisplayName() . " to cart");
        return $this->redirectToRoute($entity->getTypeName() . '_details', ['id' => $id]);
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function removeFromCart(CartItem $item, CartService $cartService,): RedirectResponse
    {
        $cart = $cartService->getCurrentCart();
        if ($cart->getItems()->contains($item)) {
            $cart->getItems()->removeElement($item);
        }
        $cartService->save($cart);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/set_delivery_address', name: 'cart_delivery_address_set')]
    public function deliveryAddress(Request $request, CartService $cartService): Response
    {
        $deliveryAddressId = $request->request->get("addrId");
        $cartService->setDefaultDeliveryAddress($deliveryAddressId);
        return new Response("ok");
    }

    #[Route('/cart/', name: 'cart_index')]
    public function show(Request $request, CartManager $cartManager, CartCalculator $cartCalculator): Response
    {
        $cart = $cartManager->getCurrentCart();
        $payment = $cartCalculator->calculatePayment($cart);
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'payment' => $payment
        ]);
    }
}
