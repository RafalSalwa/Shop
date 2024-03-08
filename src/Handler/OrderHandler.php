<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\CartService;
use App\Service\OrderService;
use App\Service\PaymentService;
use Throwable;
use function dd;

final readonly class OrderHandler
{
    public function __construct(
        private CartService $cartService,
        private PaymentService $paymentService,
        private OrderService $orderService,
    ) {
    }

    public function createPendingOrder(): void
    {
        try {
            $cart = $this->cartService->getCurrentCart();
            $order = $this->orderService->createPending($cart);
            $this->paymentService->createPendingPayment($order);
        } catch (Throwable $exception) {
            dd($exception->getMessage());
        }
    }
}
