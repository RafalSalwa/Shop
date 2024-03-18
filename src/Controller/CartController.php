<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ProductCartItem;
use App\Exception\Contracts\CartOperationExceptionInterface;
use App\Exception\Contracts\StockOperationExceptionInterface;
use App\Requests\CartAddJsonRequest;
use App\Service\CalculatorService;
use App\Service\CartService;
use App\Workflow\CartWorkflow;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[asController]
#[Route(path: '/cart', name: 'cart_', methods: ['GET', 'POST', 'DELETE'])]
#[IsGranted(attribute: 'ROLE_USER', statusCode: 403)]
final class CartController extends AbstractShopController
{
    #[Route(path: '/add/product/{id}/{quantity}', name: 'add')]
    public function addToCart(Request $request, int $id, int $quantity, CartWorkflow $cartWorkflow): RedirectResponse
    {
        try {
            $cartWorkflow->add($id, $quantity);
        } catch (CartOperationExceptionInterface | StockOperationExceptionInterface $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route(path: '/add', name: 'add_post', methods: ['POST'])]
    public function post(
        #[MapRequestPayload]
        CartAddJsonRequest $cartAddJsonRequest,
        CartWorkflow $cartWorkflow,
    ): JsonResponse {
        try {
            $cartWorkflow->add($cartAddJsonRequest->getId(), $cartAddJsonRequest->getQuantity());
        } catch (CartOperationExceptionInterface | StockOperationExceptionInterface $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json('ok', Response::HTTP_OK);
    }

    #[Route(path: '/remove/{id}', name: 'remove', methods: ['DELETE'])]
    public function removeFromCart(ProductCartItem $cartItem, CartWorkflow $cartWorkflow): JsonResponse
    {
        try {
            $cartWorkflow->remove($cartItem);
        } catch (CartOperationExceptionInterface | StockOperationExceptionInterface $exception) {
            $this->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json('ok');
    }

    #[Route(path: '/coupon/apply', name: 'coupon_apply', methods: ['POST'])]
    public function addCoupon(Request $request, CartWorkflow $cartWorkflow): Response
    {
        try {
            $couponCode = $request->request->get('coupon');
            $cartWorkflow->applyCouponCode($couponCode);
        } catch (CartOperationExceptionInterface $exception) {
            $this->addFlash('info', $exception->getMessage());
        }

        return new RedirectResponse($this->generateUrl('cart_index'));
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function show(CartService $cartService, CalculatorService $cartCalculator): Response
    {
        $cart = $cartService->getCurrentCart();

        return $this->render(
            'cart/index.html.twig',
            [
                'cart' => $cart,
                'summary' => $cartCalculator->calculateSummary($cart->getTotalAmount(), $cart->getCoupon()),
            ],
        );
    }
}
