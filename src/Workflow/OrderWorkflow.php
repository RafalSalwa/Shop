<?php

declare(strict_types=1);

namespace App\Workflow;

use App\Entity\Order;
use App\Enum\PaymentProvider;
use App\Exception\ItemNotFoundException;
use App\Exception\OrderOperationException;
use App\Exception\ProductStockDepletedException;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\PaymentService;
use ValueError;

final readonly class OrderWorkflow
{
    public function __construct(
        private CartService $cartService,
        private PaymentService $paymentService,
        private OrderService $orderService,
    ) {
    }

    /**
     * @throws ProductStockDepletedException
     * @throws OrderOperationException
     */
    public function createPendingOrder(string $paymentOperator): Order
    {
        try {
            $paymentType = PaymentProvider::from($paymentOperator);
            $cart = $this->cartService->getCurrentCart();
            $order = $this->orderService->createPending($cart);
            $this->paymentService->createPayment($order, $paymentType);
            $this->cartService->clearCart();

            return $order;
        } catch (ItemNotFoundException $exception) {
            throw new OrderOperationException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (ValueError $exception) {
            throw new OrderOperationException('Wrong payment type provided', $exception->getCode(), $exception);
        }
    }

    public function confirmOrder(Order $order): void
    {
        $this->paymentService->confirmPayment($order);
        $this->orderService->confirmOrder($order);

        $this->cartService->confirmCart();
    }
}
