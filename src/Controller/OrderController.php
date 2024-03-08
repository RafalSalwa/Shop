<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Form\PaymentType;
use App\Handler\OrderHandler;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\PaymentService;
use App\Service\TaxCalculatorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[asController]
#[Route(path: '/order', name: 'order_', methods: ['GET', 'POST'])]
final class OrderController extends AbstractShopController
{
    #[Route(path: '/create/', name: 'create_pending', methods: ['POST'])]
    public function createPendingOrder(Request $request, OrderHandler $orderHandler): Response
    {
        $paymentType = $request->request->get('payment_type');
        $pendingOrder = $orderHandler->createPendingOrder();

        if (0 !== $order->getId()) {
            $cartService->clearCart();
        }

        return $this->redirectToRoute(
            'order_show',
            [
                'id' => $order->getId(),
            ],
        );
    }

    #[Route(path: '/pending/{id}', name: 'show')]
    public function pending(
        Request $request,
        Order $order,
        OrderService $orderService,
        PaymentService $paymentService,
        CartService $cartService,
    ): Response {
        $this->denyAccessUnlessGranted('view', $order, 'Access denied: You can only view pending orders.');

        $payment = $order->getLastPayment();
        $form    = $this->createForm(PaymentType::class, $payment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (true === $form->get('yes')->isClicked()) {
                $paymentService->confirmPayment($payment);
                $orderService->confirmOrder($order);

                $cartService->confirmCart();

                $cartService->clearCart();

                return $this->redirectToRoute(
                    'order_summary',
                    [
                        'id' => $order->getId(),
                    ],
                );
            }

            if (true === $form->get('no')->isClicked()) {
                return $this->redirectToRoute(
                    'order_index',
                    ['page' => 1],
                );
            }
        }

        return $this->render(
            'order/payment.html.twig',
            [
                'order'   => $order,
                'payment' => $payment,
                'form'    => $form->createView(),
            ],
        );
    }

    #[Route(path: '/summary/{id}', name: 'summary')]
    public function summaryOrder(int $id, OrderService $orderService, TaxCalculatorService $taxCalculator): Response
    {
        $order = $orderService->fetchOrderDetails($id);

        $orderService->proceedSubscriptionsIfAny($order);
        $orderService->deserializeOrderItems($order);

        $taxCalculator->calculateOrderTax($order);

        return $this->render(
            'order/summary.html.twig',
            ['order' => $order],
        );
    }

    #[Route(path: '/orders/{page<\d+>}', name: 'index')]
    public function index(int $page, #[CurrentUser] User $user, OrderService $service): Response
    {
        $orders = $service->fetchOrders($user, $page);

        return $this->render(
            'order/index.html.twig',
            ['paginator' => $orders],
        );
    }

    #[Route(path: '/{id<\d+>}', name: 'details')]
    public function show(int $id, OrderService $orderService): Response
    {
        $order = $orderService->fetchOrderDetails($id);
        $orderService->deserializeOrderItems($order);

        return $this->render(
            'order/details.html.twig',
            ['order' => $order],
        );
    }
}
