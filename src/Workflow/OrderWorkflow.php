<?php

declare(strict_types=1);

namespace App\Workflow;

use App\Entity\Order;
use App\Enum\PaymentProvider;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\PaymentService;
use Throwable;

use function dd;

final readonly class OrderWorkflow
{
    public function __construct(
        private CartService $cartService,
        private PaymentService $paymentService,
        private OrderService $orderService,
    ) {
    }

    public function createPendingOrder(string $paymentOperator): Order
    {
        try {
            $paymentType = PaymentProvider::from($paymentOperator);
            $cart = $this->cartService->getCurrentCart();
            $order = $this->orderService->createPending($cart);
            $this->paymentService->createPayment($order, $paymentType);
            $this->cartService->clearCart();

            return $order;
        } catch (Throwable $exception) {
            dd($exception->getMessage(), $exception::class, $exception->getTraceAsString());
        }
    }

    public function confirmOrder(Order $order): void
    {
        $this->paymentService->confirmPayment($order);
        $this->orderService->confirmOrder($order);

        $this->cartService->confirmCart();
    }
}
